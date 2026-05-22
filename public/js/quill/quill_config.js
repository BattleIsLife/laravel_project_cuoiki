const toolbarOptions = [
    ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
    ['link', 'image'],

    [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
    [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
    [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
    [{ 'direction': 'rtl' }],                         // text direction

    [{ 'header': [1, 2, false] }],

    [{ 'align': [] }],

    ['clean']                                         // remove formatting button
];

const quill = new Quill('#editor', {
    theme: 'snow',
    modules: { toolbar: toolbarOptions }
});

quill.on('text-change', function() {
    document.getElementById('hiddenContent').value = quill.root.innerHTML;
});