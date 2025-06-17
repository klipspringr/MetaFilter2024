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
        document.addEventListener('editor:clear', (event) => {
            if (event.detail.editorId === editorId) {
                editor.setData('');
            }
        });

        // Update the component's content property when the force sync event is received
        document.addEventListener('livewire:force-sync', () => {
            component.$wire.$set('content', editor.getData());
        });
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
    })
});
