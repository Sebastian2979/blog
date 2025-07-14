// TinyMCE importieren
import tinymce from 'tinymce/tinymce';

// Themes
import 'tinymce/themes/silver';

// Icons
import 'tinymce/icons/default';

// Plugins (nur die, die wirklich gebraucht werden)
import 'tinymce/plugins/link';
import 'tinymce/plugins/image';
import 'tinymce/plugins/code';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/table';
import 'tinymce/plugins/wordcount';
import 'tinymce/plugins/fullscreen';
import 'tinymce/plugins/media';

// Skin und Content CSS
import 'tinymce/skins/ui/oxide/skin.css';
import 'tinymce/skins/content/default/content.css';

// Editor initialisieren, wenn #content vorhanden ist
document.addEventListener('DOMContentLoaded', function () {
    const editorElement = document.querySelector('#content');
    if (editorElement) {
        tinymce.init({
            selector: '#content',
            plugins: 'link image code lists table wordcount fullscreen media',
            toolbar: 'undo redo | blocks fontsizeselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist | link image media table | code fullscreen',
            file_picker_types: 'file image medium',
            menubar: false,
            branding: false,
            height: 500,
            fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
            content_css: false, // Nutzt dein Tailwind im Frontend
            base_url: '/vendor/tinymce', // <<< wichtig: Pfad zu TinyMCE
            automatic_uploads: true,


            setup: function (editor) {
                editor.on('change', function () {
                    editor.save();
                });
            },

            images_upload_handler: function (blobInfo, progress) {
                return new Promise((resolve, reject) => {
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', '/tinymce-upload'); // Deine Laravel Route
                    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);

                    xhr.upload.onprogress = (e) => {
                        progress(e.loaded / e.total * 100); // Fortschrittsanzeige im Dialog
                    };

                    xhr.onload = () => {
                        if (xhr.status < 200 || xhr.status >= 300) {
                            reject('HTTP Error: ' + xhr.status);
                            return;
                        }

                        let json;
                        try {
                            json = JSON.parse(xhr.responseText);
                        } catch (err) {
                            reject('Invalid JSON: ' + xhr.responseText);
                            return;
                        }

                        if (!json || typeof json.location !== 'string') {
                            reject('Invalid response: no location URL');
                            return;
                        }

                        console.log('✅ Uploaded image URL:', json.location);
                        resolve(json.location); // ✔ TinyMCE trägt Bild ins Editorfeld ein
                    };

                    xhr.onerror = () => {
                        reject('Upload failed: XHR error ' + xhr.status);
                    };

                    const formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());

                    xhr.send(formData);
                });
            }



        });
    }
});