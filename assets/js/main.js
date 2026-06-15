function updatePreview() {
    const editor = document.getElementById('htmlEditor');
    const preview = document.getElementById('previewFrame');

    if (editor && preview) {
        preview.srcdoc = editor.value;
    }
}

function resetEditor(defaultCode) {
    const editor = document.getElementById('htmlEditor');
    if (editor) {
        editor.value = defaultCode;
        updatePreview();
    }
}

document.addEventListener('DOMContentLoaded', function () {
    updatePreview();
});
