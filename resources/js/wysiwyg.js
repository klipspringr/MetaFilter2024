// Unique symbol for identifying cleanup functions.
const cleanupKey = Symbol('cleanup');

// Set up a single textarea with CKEditor
async function initializeEditor(textarea, component) {
    try {
        let globalEditorConfig = null;
        try {
            globalEditorConfig = JSON.parse(document.querySelector('meta[name="ckeditor-config"]')?.content || 'null');
        } catch (e) {
            // Ignore
            console.debug('Unable to parse CKEditor config from meta tag', e);
        }

        let localEditorConfig = null;
        try {
            localEditorConfig = JSON.parse(textarea.dataset.editorConfig || 'null');
        } catch (e) {
            // Ignore
            console.debug('Unable to parse CKEditor config from textarea data-editor-config', e);
        }

        const editorConfig = {
            ...(globalEditorConfig || {}),
            ...(localEditorConfig || {}),
            toolbar: {
                items: [
                    'bold', 'italic', 'link',
                    'bulletedList', 'numberedList', 'blockQuote', '|',
                    'heading', 'insertTable', 'mediaEmbed', '|',
                    'undo', 'redo'
                ],
                // Collapse into three dots menu when the toolbar is full.
                shouldNotGroupWhenFull: false
            }
        }

        const editor = await ClassicEditor.create(textarea, editorConfig);
        const editorId = textarea.dataset.editorId;

        // Listen for changes to the editor content and update the component's content property.
        const sync = () => {
            const content = editor.getData();
            textarea.value = content;
            component.$wire.$set('content', content, false);
        };

        editor.model.document.on('change:data', sync);

        // Also sync when the editor loses focus.
        editor.ui.focusTracker.on('change:isFocused', ( _event, _name, isFocused ) => {
            if (!isFocused) sync();
        });

        // Reset the editor content when we receive a notification from the backend.
        const clearEditor = (event) => {
            if (event.detail.editorId === editorId) {
                editor.setData('');
                textarea.value = '';
            }
        };

        document.addEventListener('editor:clear', clearEditor);

        // Update the component's content property when the force sync event is received
        const syncAndFlush = () => {
            const content = editor.getData();
            textarea.value = content;
            component.$wire.$set('content', content);
        };

        document.addEventListener('livewire:force-sync', syncAndFlush);

        textarea[cleanupKey] = () => {
            document.removeEventListener('editor:clear', clearEditor);
            document.removeEventListener('livewire:force-sync', syncAndFlush);
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
