<script>
    (() => {
        const prefix = 'b64:';

        function encodeUtf8(value) {
            const bytes = new TextEncoder().encode(value);
            let binary = '';

            for (let offset = 0; offset < bytes.length; offset += 0x8000) {
                binary += String.fromCharCode(...bytes.subarray(offset, offset + 0x8000));
            }

            return prefix + btoa(binary);
        }

        function syncEditors(form) {
            const fields = Array.from(form.querySelectorAll('textarea[name]'));
            const editables = Array.from(form.querySelectorAll('.ck-editor__editable'));

            editables.forEach((editable, index) => {
                const wrapper = editable.closest('.ck-editor');
                let source = wrapper ? wrapper.previousElementSibling : null;

                while (source && source !== form && !source.matches('textarea[name]')) {
                    source = source.previousElementSibling;
                }

                // The fallback covers layouts where CKEditor moves its wrapper.
                const field = source && source.matches('textarea[name]') ? source : fields[index];

                if (field) {
                    field.value = editable.innerHTML;
                }
            });

            fields.forEach((field) => {
                if (field.dataset.htmlEncoded !== '1') {
                    field.value = encodeUtf8(field.value);
                    field.dataset.htmlEncoded = '1';
                }
            });
        }

        // Capture runs before CKEditor or other bubbling submit handlers.
        document.addEventListener('submit', (event) => {
            const form = event.target.closest('form[data-encode-html]');

            if (form) {
                syncEditors(form);
                // CKEditor also syncs raw HTML during bubbling; keep it from
                // replacing the encoded source values before the request leaves.
                event.stopImmediatePropagation();
            }
        }, true);
    })();
</script>
