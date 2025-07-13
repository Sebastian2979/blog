import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

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
            menubar: false,
            branding: false,
            height: 500,
            fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
            content_css: false, // Nutzt dein Tailwind im Frontend
            base_url: '/vendor/tinymce', // <<< wichtig: Pfad zu TinyMCE
            setup: function (editor) {
                editor.on('change', function () {
                    editor.save();
                });
            }
        });
    }
});
