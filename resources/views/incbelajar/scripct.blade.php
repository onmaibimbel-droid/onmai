    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

     <!-- TinyMCE -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

<script>
document.querySelectorAll('.editor').forEach((el) => {
    ClassicEditor.create(el, {
    toolbar: [
        'heading',
        '|',
        'bold',
        'italic',
        'alignment',
        'bulletedList',
        'numberedList',
        '|',
        'link',
        'insertTable',
        'imageUpload',
        '|',
        'undo',
        'redo',
        'sourceEditing' // 🔥 penting
    ],

    // 🔥 WAJIB biar MathJax jalan
    htmlSupport: {
        allow: [
            {
                name: /.*/,
                attributes: true,
                classes: true,
                styles: true
            }
        ]
    }
    }).then(editor => {
        editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
            return new MyUploadAdapter(loader);
        };
    }).catch(error => {
        console.error(error);
    });
});
document.addEventListener("input", function () {
    if (window.MathJax) {
        MathJax.typesetPromise();
    }
});
</script>

<script>
class MyUploadAdapter {
    constructor(loader) {
        this.loader = loader;
    }

    upload() {
        return this.loader.file.then(file => {
            return new Promise((resolve, reject) => {
                const formData = new FormData();
                formData.append('upload', file);
                formData.append('_token', '{{ csrf_token() }}');

                fetch("{{ route('soal.upload') }}", {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.url) {
                        resolve({ default: data.url });
                    } else {
                        reject(data.error || 'Upload gagal');
                    }
                })
                .catch(error => {
                    reject(error);
                });
            });
        });
    }

    abort() {}
}
</script>

  <script>
    setTimeout(() => {
        const popup = document.getElementById('popup-success');
        if (popup) {
            popup.style.opacity = '0';
            popup.style.transition = 'opacity 0.4s ease';
            setTimeout(() => popup.remove(), 400);
        }
    }, 2000); // 2 detik
</script>








