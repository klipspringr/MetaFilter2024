// Unique symbol for identifying cleanup functions.
const cleanupKey = Symbol('cleanup');

// Set up a single textarea with CKEditor
async function initializeEditor(textarea, component) {
    try {
        const editorConfig = JSON.parse(document.querySelector('meta[name="ckeditor-config"]')?.content || 'null') || {}
        const editor = await ClassicEditor.create(textarea, editorConfig);
        const editorId = textarea.dataset.editorId;

        // Listen for changes to the editor content and update the component's content property
        editor.model.document.on('change:data', () => {
            component.$wire.$set('content', editor.getData());
        });

        // Reset the editor content when we receive a notification from the backend.
        const onEditorClear = (event) => {
            if (event.detail.editorId === editorId) {
                editor.setData('');
            }
        };

        document.addEventListener('editor:clear', onEditorClear);

        // Update the component's content property when the force sync event is received
        const onForceSync = () => {
            component.$wire.$set('content', editor.getData());
        };

        document.addEventListener('livewire:force-sync', onForceSync);

        textarea[cleanupKey] = () => {
            document.removeEventListener('editor:clear', onEditorClear);
            document.removeEventListener('livewire:force-sync', onForceSync);
            editor.destroy().catch(e => console.error('Error destroying editor', e));
        };
    } catch (error) {
        console.error('Unable to initialize CKEditor', error);
    }
}

// Initialize WysiwygComponent editors when the elements are initialized by Livewire.
document.addEventListener('livewire:init', () => {
    Livewire.hook('element.init', ({ component, el }) => {
        switch (component.name) {
            case 'wysiwyg.wysiwyg-component':
                if (el instanceof HTMLTextAreaElement) {
                    initializeEditor(el, component);
                }
                break;
            default:
                break;
        }
    });

    Livewire.hook('morph.removing', ({ el }) => {
        // Check for textarea elements that need cleanup.
        for (const textarea of el.querySelectorAll('textarea')) {
            if (typeof textarea[cleanupKey] === 'function') {
                textarea[cleanupKey]();
            }
        }
    });
});
