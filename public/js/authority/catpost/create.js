document.addEventListener('DOMContentLoaded', function () {
    const imageInput = document.getElementById('image');
    const videoInput = document.getElementById('video');
    const container = document.getElementById('preview-container');

    let selectedImages = [];
    let selectedVideo = null;

    // 画像プレビュー
    imageInput.addEventListener('change', function (event) {
        const files = Array.from(event.target.files);

        if (selectedImages.length + files.length > 4) {
            alert('画像は最大4枚まで選択できます。');
            imageInput.value = '';
            return;
        }

        selectedImages.push(...files);
        renderPreview();
    });

    // 動画プレビュー
    videoInput.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (!file) return;

        selectedVideo = file;
        renderPreview();
    });

    function renderPreview() {
        container.innerHTML = '';

        // 画像
        selectedImages.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const wrapper = document.createElement('div');
                wrapper.classList.add('preview-item');
                wrapper.dataset.index = index;

                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('preview-image');

                const removeBtn = document.createElement('button');
                removeBtn.textContent = '✕';
                removeBtn.classList.add('remove-btn');
                removeBtn.addEventListener('click', function () {
                    selectedImages.splice(index, 1);
                    renderPreview();
                });

                wrapper.appendChild(img);
                wrapper.appendChild(removeBtn);
                container.appendChild(wrapper);
            };
            reader.readAsDataURL(file);
        });

        // 動画
        if (selectedVideo) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const wrapper = document.createElement('div');
                wrapper.classList.add('preview-item');

                const video = document.createElement('video');
                video.src = e.target.result;
                video.controls = true;
                video.classList.add('preview-video');

                const removeBtn = document.createElement('button');
                removeBtn.textContent = '✕';
                removeBtn.classList.add('remove-btn');
                removeBtn.addEventListener('click', function () {
                    selectedVideo = null;
                    videoInput.value = '';
                    renderPreview();
                });

                wrapper.appendChild(video);
                wrapper.appendChild(removeBtn);
                container.appendChild(wrapper);
            };
            reader.readAsDataURL(selectedVideo);
        }

        updateInputFiles();
    }

    // FileList更新
    function updateInputFiles() {
        const dataTransfer = new DataTransfer();
        selectedImages.forEach(file => dataTransfer.items.add(file));
        imageInput.files = dataTransfer.files;
    }
});
