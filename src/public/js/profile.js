document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('fileInput');
    const imagePreview = document.querySelector('.profile-image');

    if (fileInput && imagePreview) {
        fileInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    // CSS変数を更新（var(--bg-url) に反映される）
                    imagePreview.style.setProperty('--bg-url', `url('${e.target.result}')`);
                };
                reader.readAsDataURL(file);
            }
        });
    }
});