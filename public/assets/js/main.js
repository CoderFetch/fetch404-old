var tooltipSettings = {html: true, animation: false};
var summernoteSettings = {
    height: 300,
    minHeight: null,
    maxHeight: null,
    toolbar: [
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['font', ['strikethrough']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['height', ['height']],
        ['style', ['picture', 'link', 'table', 'hr']],
        ['misc', ['codeview']],
    ]
};
$(document).ready(function()
{
    $('[data-type=tooltip]').tooltip(tooltipSettings);
    $('[data-type=summernote]').summernote(summernoteSettings || {});
});