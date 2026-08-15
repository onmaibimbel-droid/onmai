{{--
    Perender rumus untuk soal hasil import Word.

    Soal disimpan sebagai MathML. Browser modern sebenarnya sudah bisa
    merendernya sendiri, tapi hasilnya berbeda-beda antar browser, jadi
    MathJax dipakai supaya tampilannya seragam di semua perangkat siswa.
--}}
<style>
    .rumus-soal math { font-size: 1.05em; }
    .rumus-soal table { border-collapse: collapse; margin: 8px 0; }
    .rumus-soal table td { border: 1px solid #d1d5db; padding: 4px 10px; font-size: .9em; }
</style>

<script>
    window.MathJax = {
        options: { enableMenu: false },
        startup: { typeset: true }
    };
</script>
<script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js" id="MathJax-script" async></script>
