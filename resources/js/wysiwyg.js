document.addEventListener('DOMContentLoaded', function () {
    const editorConfig = JSON.parse(document.querySelector('meta[name="ckeditor-config"]')?.content || 'null') || {};

    function initializeEditors() {
        document.querySelectorAll('.wysiwyg:not(.ck-editor__editable)').forEach(textarea => {
            textarea.classList.add('comment-textarea');
            ClassicEditor
                .create(textarea, editorConfig)
                .then(editor => {
                    editor.model.document.on('change:data', () => {
                        Livewire.dispatch('editorUpdated', {
                            editorId: textarea.dataset.editorId,
                            content: editor.getData()
                        });
                    });
                })
                .catch(error => console.error(error));
        });
    }

    // Initialize editors on page load
    initializeEditors();

    // Initialize editors when Livewire updates the DOM
    document.addEventListener('livewire:load', function () {
        Livewire.hook('message.processed', (message, component) => {
            initializeEditors();
        });
    });
});
