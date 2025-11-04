'use strict';

document.addEventListener('DOMContentLoaded', function() {
    const $form = document.getElementById('catpostForm');
    const $imageInput = document.getElementById('imageInput');
    const $videoInput = document.getElementById('videoInput');
    const $selectImageBtn = document.getElementById('selectImageBtn');
    const $selectVideoBtn = document.getElementById('selectVideoBtn');
    const $previewContainer = document.getElementById('preview-container');
    const $videoPreviewContainer = document.getElementById('video-preview-container');
    const $remainingNumber = document.getElementById('remaining-number');

    const MAX_IMAGE_SIZE = 2 * 1024 * 1024;
    const MAX_VIDEO_SIZE = 10 * 1024 * 1024;
    const MAX_IMAGES = 3;

    let selectedImages = [];
    let selectedVideo = null;

    // ===============================
    // IndexedDB 設定（動画保存用）
    // ===============================
    let db;
    const DB_NAME = 'CatPostMediaDB';
    const STORE_NAME = 'videos';

    function initDB() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open(DB_NAME, 1);
            request.onupgradeneeded = e => {
                const db = e.target.result;
                if (!db.objectStoreNames.contains(STORE_NAME)) {
                    db.createObjectStore(STORE_NAME, { keyPath: 'id' });
                }
            };
            request.onsuccess = e => {
                db = e.target.result;
                resolve();
            };
            request.onerror = reject;
        });
    }

    function saveVideoToDB(file) {
        return new Promise((resolve, reject) => {
            if (!db) return reject('DB未初期化');
            const tx = db.transaction(STORE_NAME, 'readwrite');
            const store = tx.objectStore(STORE_NAME);
            store.put({ id: 'saved_video', file });
            tx.oncomplete = resolve;
            tx.onerror = reject;
        });
    }

    function getVideoFromDB() {
        return new Promise((resolve, reject) => {
            if (!db) return resolve(null);
            const tx = db.transaction(STORE_NAME, 'readonly');
            const store = tx.objectStore(STORE_NAME);
            const request = store.get('saved_video');
            request.onsuccess = e => resolve(e.target.result?.file || null);
            request.onerror = reject;
        });
    }

    function clearVideoDB() {
        if (!db) return;
        const tx = db.transaction(STORE_NAME, 'readwrite');
        tx.objectStore(STORE_NAME).delete('saved_video');
    }

    // ===============================
    // Base64 ユーティリティ
    // ===============================
    function toBase64(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = () => resolve(reader.result);
            reader.onerror = reject;
            reader.readAsDataURL(file);
        });
    }

    function fromBase64(base64, name, type) {
        const arr = base64.split(',');
        const bstr = atob(arr[1]);
        let n = bstr.length;
        const u8arr = new Uint8Array(n);
        while (n--) u8arr[n] = bstr.charCodeAt(n);
        return new File([u8arr], name, { type });
    }

    const formatFileSize = bytes => (bytes / 1024 / 1024).toFixed(2) + 'MB';
    const remainingCount = () => MAX_IMAGES - selectedImages.length;

    function updateRemaining() {
        const remain = remainingCount();
        $remainingNumber.textContent = remain;
        $selectImageBtn.disabled = remain <= 0;
        $selectImageBtn.style.opacity = remain <= 0 ? '0.5' : '1';
    }

    // ===============================
    // メディア保存
    // ===============================
    async function saveToSession() {
        const images = await Promise.all(selectedImages.map(async f => ({
            name: f.name, type: f.type, data: await toBase64(f)
        })));
        sessionStorage.setItem('catpost_images', JSON.stringify(images));

        if (selectedVideo) {
            await saveVideoToDB(selectedVideo);
        }
    }

    // ===============================
    // メディア復元
    // ===============================
    async function restoreFromSession() {
        const savedImages = JSON.parse(sessionStorage.getItem('catpost_images') || '[]');
        const savedVideo = await getVideoFromDB();

        // 画像復元
        if (savedImages.length) {
            savedImages.forEach((imgData) => {
                const file = fromBase64(imgData.data, imgData.name, imgData.type);
                selectedImages.push(file);

                const img = document.createElement('img');
                img.src = imgData.data;
                img.classList.add('preview-image');

                const item = document.createElement('div');
                item.className = 'preview-item';
                const btn = document.createElement('button');
                btn.textContent = '×';
                btn.classList.add('remove-btn');
                btn.onclick = () => {
                    item.remove();
                    selectedImages = selectedImages.filter(f => f !== file);
                    updateRemaining();
                };
                item.append(img, btn);
                $previewContainer.appendChild(item);
            });
        }

        // 動画復元
        if (savedVideo) {
            selectedVideo = savedVideo;
            const videoElem = document.createElement('video');
            videoElem.controls = true;
            videoElem.src = URL.createObjectURL(savedVideo);
            videoElem.classList.add('preview-video');

            const item = document.createElement('div');
            item.className = 'preview-item';
            const btn = document.createElement('button');
            btn.textContent = '×';
            btn.classList.add('remove-btn');
            btn.onclick = () => {
                item.remove();
                selectedVideo = null;
                clearVideoDB();
                $selectVideoBtn.disabled = false;
                $selectVideoBtn.style.opacity = '1';
            };
            item.append(videoElem, btn);
            $videoPreviewContainer.innerHTML = '';
            $videoPreviewContainer.appendChild(item);
            $selectVideoBtn.disabled = true;
            $selectVideoBtn.style.opacity = '0.5';
        }

        updateRemaining();
    }

    // ===============================
    // イベント処理
    // ===============================
    $selectImageBtn.addEventListener('click', () => $imageInput.click());
    $imageInput.addEventListener('change', (e) => {
        const files = Array.from(e.target.files);
        const remain = remainingCount();
        if (files.length > remain) {
            alert(`あと${remain}枚までです`);
            return;
        }
        files.forEach(file => {
            if (file.size > MAX_IMAGE_SIZE) {
                alert(`${file.name} は ${formatFileSize(MAX_IMAGE_SIZE)} 以下にしてください`);
                return;
            }
            selectedImages.push(file);
            const reader = new FileReader();
            reader.onload = (ev) => {
                const img = document.createElement('img');
                img.src = ev.target.result;
                img.classList.add('preview-image');
                const item = document.createElement('div');
                item.className = 'preview-item';
                const btn = document.createElement('button');
                btn.textContent = '×';
                btn.classList.add('remove-btn');
                btn.onclick = () => {
                    item.remove();
                    selectedImages = selectedImages.filter(f => f !== file);
                    updateRemaining();
                };
                item.append(img, btn);
                $previewContainer.appendChild(item);
            };
            reader.readAsDataURL(file);
        });
        updateRemaining();
    });

    $selectVideoBtn.addEventListener('click', () => $videoInput.click());
    $videoInput.addEventListener('change', async (e) => {
        const file = e.target.files[0];
        if (!file) return;
        if (file.size > MAX_VIDEO_SIZE) {
            alert('動画は10MB以下で選択してください');
            return;
        }
        selectedVideo = file;
        const videoElem = document.createElement('video');
        videoElem.controls = true;
        videoElem.src = URL.createObjectURL(file);
        videoElem.classList.add('preview-video');
        const item = document.createElement('div');
        item.className = 'preview-item';
        const btn = document.createElement('button');
        btn.textContent = '×';
        btn.classList.add('remove-btn');
        btn.onclick = () => {
            item.remove();
            selectedVideo = null;
            clearVideoDB();
            $selectVideoBtn.disabled = false;
            $selectVideoBtn.style.opacity = '1';
        };
        item.append(videoElem, btn);
        $videoPreviewContainer.innerHTML = '';
        $videoPreviewContainer.appendChild(item);
        $selectVideoBtn.disabled = true;
        $selectVideoBtn.style.opacity = '0.5';
    });

    // ===============================
    // フォーム送信
    // ===============================
    $form.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (selectedImages.length === 0) {
            alert('最低1枚の画像を選択してください');
            return;
        }
        await saveToSession();
        $form.submit();
    });

    // ===============================
    // 初期処理
    // ===============================
    (async () => {
        await initDB();
        if (document.querySelector('.alert-danger')) {
            await restoreFromSession();
        } else {
            sessionStorage.removeItem('catpost_images');
            clearVideoDB();
        }
        updateRemaining();
    })();
});
