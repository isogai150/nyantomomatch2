'use strict';

document.addEventListener('DOMContentLoaded', function () {
    const imageInput = document.getElementById('image');
    const videoInput = document.getElementById('video');
    const container = document.getElementById('preview-container');

    let selectedImages = [];
    let selectedVideo = null;

    // 一時保存された画像の削除ボタン
    document.querySelectorAll('.remove-temp-image').forEach(btn => {
        btn.addEventListener('click', function() {
            const index = this.dataset.index;
            // Ajaxで削除リクエスト
            fetch(`/temp-image/delete/${index}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => {
                this.closest('.preview-item').remove();
            });
        });
    });

    // 一時保存された動画の削除ボタン
    const removeTempVideoBtn = document.querySelector('.remove-temp-video');
    if (removeTempVideoBtn) {
        removeTempVideoBtn.addEventListener('click', function() {
            fetch('/temp-video/delete', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => {
                this.closest('.preview-item').remove();
            });
        });
    }

    // 画像プレビュー
    imageInput.addEventListener('change', function (event) {
        const files = Array.from(event.target.files);
        const currentTempCount = document.querySelectorAll('[data-temp-index]').length;

        // 動画は含めず、画像のみで3枚チェック
        if (currentTempCount + selectedImages.length + files.length > 3) {
            alert('画像は最大3枚まで選択できます。');
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

        // 既に動画がある場合はアラート
        const hasTempVideo = document.querySelector('.remove-temp-video') !== null;
        if (hasTempVideo || selectedVideo) {
            alert('動画は最大1本までです。');
            videoInput.value = '';
            return;
        }

        selectedVideo = file;
        renderPreview();
    });

    function renderPreview() {
        // 新規選択分のみ描画（一時保存分は残す）
        const newPreviews = container.querySelectorAll('.preview-item:not([data-temp-index]):not(.temp-video)');
        newPreviews.forEach(el => el.remove());

        // 画像
        selectedImages.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const wrapper = document.createElement('div');
                wrapper.classList.add('preview-item');
                wrapper.dataset.newIndex = index;

                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('preview-image');

                const removeBtn = document.createElement('button');
                removeBtn.textContent = '✕';
                removeBtn.type = 'button';
                removeBtn.classList.add('remove-btn');
                removeBtn.addEventListener('click', function () {
                    selectedImages.splice(index, 1);
                    renderPreview();
                    updateInputFiles();
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
                wrapper.classList.add('preview-item', 'temp-video');

                const video = document.createElement('video');
                video.src = e.target.result;
                video.controls = true;
                video.classList.add('preview-video');

                const removeBtn = document.createElement('button');
                removeBtn.textContent = '✕';
                removeBtn.type = 'button';
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
