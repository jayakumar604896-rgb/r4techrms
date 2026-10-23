<script src="{{ asset('assets/admin/global/plugins/tinymce/js/tinymce/jquery.tinymce.min.js') }}"></script>
<script src="{{ asset('assets/admin/global/plugins/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script>
$.ajaxSetup({
    beforeSend: function(xhr, type) {
        if (!type.crossDomain) {
            xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
        }
    },
});

tinymce.init({
    selector: '.textarea',
    height: 150,
    entity_encoding: "raw",
    forced_root_block: '',
    convert_urls: false,
    link_context_toolbar: true,
    plugins: [
        'advlist autolink lists image link',  // Added 'link' plugin
        'searchreplace visualblocks code fullscreen',
        'media table contextmenu paste code'
    ],

    toolbar: 'insertfile undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',  // Added 'link'
    
    relative_urls: true,
    images_upload_url: "{{ route('tinymce.image_upload.front') }}",

    images_upload_handler: function (blobInfo, success, failure) {
        var xhr, formData;
        xhr = new XMLHttpRequest();
        xhr.withCredentials = false;
        xhr.open('POST', "{{ route('tinymce.image_upload.front') }}");

        xhr.onload = function () {
            var json;
            if (xhr.status != 200) {
                failure('HTTP Error: ' + xhr.status);
                return;
            }

            json = JSON.parse(xhr.responseText);

            if (!json || typeof json.location != 'string') {
                failure('Invalid JSON: ' + xhr.responseText);
                return;
            }
            success(json.location);
        };

        formData = new FormData();
        var _token = $('input[name=_token]').val();
        formData.append('file', blobInfo.blob(), blobInfo.filename());  // Changed 'image' to 'file'
        formData.append('_token', _token);
        xhr.send(formData);
    }
});

</script>
