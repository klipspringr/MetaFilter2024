document.addEventListener('DOMContentLoaded', function () {
    const editorConfig = JSON.parse(document.querySelector('meta[name="ckeditor-config"]')?.content || '{}');

    document.querySelectorAll('.wysiwyg').forEach(textarea => {
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
});
