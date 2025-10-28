'use strict';

document.addEventListener('DOMContentLoaded', function () {
    const imageInput = document.getElementById('image');
    const videoInput = document.getElementById('video');
    const container = document.getElementById('preview-container');

document.addEventListener('DOMContentLoaded', function() {
    const $previewContainer = document.getElementById('preview-container');
    const $videoPreviewContainer = document.getElementById('video-preview-container');
    const $imageInput = document.getElementById('imageInput');
    const $videoInput = document.getElementById('videoInput');
    const $remainingNumber = document.getElementById('remaining-number');
    const $selectImageBtn = document.getElementById('selectImageBtn');
    const $selectVideoBtn = document.getElementById('selectVideoBtn');
    const $form = document.querySelector('form');
    
    // 定数
    const MAX_IMAGE_SIZE = 2 * 1024 * 1024; // 2MB
    const MAX_VIDEO_SIZE = 10 * 1024 * 1024; // 10MB
    const MAX_IMAGES = 3;
    const DB_NAME = 'CatPostDB';
    const DB_VERSION = 1;
    const STORE_NAME = 'mediaFiles';

    let selectedFiles = [];
    let selectedVideo = null;
    let db = null;

    // IndexedDBを初期化
    function initDB() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open(DB_NAME, DB_VERSION);
            
            request.onerror = () => reject(request.error);
            request.onsuccess = () => {
                db = request.result;
                resolve(db);
            };
            
            request.onupgradeneeded = (event) => {
                const db = event.target.result;
                if (!db.objectStoreNames.contains(STORE_NAME)) {
                    db.createObjectStore(STORE_NAME, { keyPath: 'id' });
                }
            };
        });
    }

    // IndexedDBにデータを保存
    function saveToIndexedDB(key, data) {
        return new Promise((resolve, reject) => {
            if (!db) {
                reject('Database not initialized');
                return;
            }
            
            const transaction = db.transaction([STORE_NAME], 'readwrite');
            const store = transaction.objectStore(STORE_NAME);
            const request = store.put({ id: key, data: data });
            
            request.onsuccess = () => resolve();
            request.onerror = () => reject(request.error);
        });
    }

    // IndexedDBからデータを取得
    function getFromIndexedDB(key) {
        return new Promise((resolve, reject) => {
            if (!db) {
                reject('Database not initialized');
                return;
            }
            
            const transaction = db.transaction([STORE_NAME], 'readonly');
            const store = transaction.objectStore(STORE_NAME);
            const request = store.get(key);
            
            request.onsuccess = () => {
                resolve(request.result ? request.result.data : null);
            };
            request.onerror = () => reject(request.error);
        });
    }

    // IndexedDBからデータを削除
    function deleteFromIndexedDB(key) {
        return new Promise((resolve, reject) => {
            if (!db) {
                reject('Database not initialized');
                return;
            }
            
            const transaction = db.transaction([STORE_NAME], 'readwrite');
            const store = transaction.objectStore(STORE_NAME);
            const request = store.delete(key);
            
            request.onsuccess = () => resolve();
            request.onerror = () => reject(request.error);
        });
    }

    // ファイルサイズをフォーマット
    function formatFileSize(bytes) {
        return (bytes / 1024 / 1024).toFixed(2) + 'MB';
    }

    // 残り画像枚数を計算
    function getRemainingImageCount() {
        const validCount = selectedFiles.filter(f => f !== null).length;
        return MAX_IMAGES - validCount;
    }

    // 残り枚数表示を更新
    function updateRemainingCount() {
        const remaining = getRemainingImageCount();
        $remainingNumber.textContent = remaining;
        
        if (remaining <= 0) {
            $selectImageBtn.disabled = true;
            $selectImageBtn.style.opacity = '0.5';
            $selectImageBtn.style.cursor = 'not-allowed';
        } else {
            $selectImageBtn.disabled = false;
            $selectImageBtn.style.opacity = '1';
            $selectImageBtn.style.cursor = 'pointer';
        }
    }

    // FileListを更新してinputに設定
    function updateInputFiles() {
        const dataTransfer = new DataTransfer();
        const validFiles = selectedFiles.filter(f => f !== null);
        
        validFiles.forEach(file => {
            dataTransfer.items.add(file);
        });
        
        $imageInput.files = dataTransfer.files;
        console.log('imageInput.files更新完了:', $imageInput.files.length, '件');
    }

    // 画像をIndexedDBに保存
    async function saveImagesToStorage() {
        const validFiles = selectedFiles.filter(f => f !== null);
        const filesData = validFiles.map(file => ({
            name: file.name,
            type: file.type,
            size: file.size,
            file: file // Fileオブジェクトをそのまま保存
        }));

        try {
            await saveToIndexedDB('images', filesData);
            console.log('画像をIndexedDBに保存しました');
        } catch (e) {
            console.warn('IndexedDBへの保存に失敗:', e);
        }
    }

    // 動画をIndexedDBに保存
    async function saveVideoToStorage() {
        if (!selectedVideo) {
            await deleteFromIndexedDB('video');
            return;
        }

        const videoData = {
            name: selectedVideo.name,
            type: selectedVideo.type,
            size: selectedVideo.size,
            file: selectedVideo // Fileオブジェクトをそのまま保存
        };

        try {
            await saveToIndexedDB('video', videoData);
            console.log('動画をIndexedDBに保存しました');
        } catch (error) {
            console.warn('IndexedDBへの保存に失敗:', error);
        }
    }

    // IndexedDBから画像を復元
    async function restoreImagesFromStorage() {
        try {
            const filesData = await getFromIndexedDB('images');
            if (!filesData || filesData.length === 0) return;

            console.log('IndexedDBから画像を復元中:', filesData.length, '件');

            for (let i = 0; i < filesData.length; i++) {
                const fileData = filesData[i];
                const file = fileData.file;
                
                selectedFiles.push(file);

                // プレビュー表示
                const reader = new FileReader();
                reader.onload = function(event) {
                    const item = document.createElement('div');
                    item.className = 'preview-item';
                    item.dataset.fileIndex = i;
                    
                    const img = document.createElement('img');
                    img.src = event.target.result;
                    img.className = 'preview-image';
                    img.style.cssText = 'width:150px; height:150px; object-fit:cover; border-radius:10px;';
                    
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'remove-btn new';
                    removeBtn.textContent = '×';
                    removeBtn.dataset.fileIndex = i;
                    
                    item.appendChild(img);
                    item.appendChild(removeBtn);
                    $previewContainer.appendChild(item);
                };
                reader.readAsDataURL(file);
            }

            updateRemainingCount();
            updateInputFiles();
        } catch (e) {
            console.error('画像の復元に失敗:', e);
            await deleteFromIndexedDB('images');
        }
    }

    // IndexedDBから動画を復元
    async function restoreVideoFromStorage() {
        try {
            const videoData = await getFromIndexedDB('video');
            if (!videoData) return;

            console.log('IndexedDBから動画を復元中');

            selectedVideo = videoData.file;

            // videoInputに設定
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(selectedVideo);
            $videoInput.files = dataTransfer.files;

            // プレビュー表示
            const item = document.createElement('div');
            item.className = 'preview-item';
            item.style.cssText = 'width:150px; height:150px;';
            
            const videoURL = URL.createObjectURL(selectedVideo);
            
            const video = document.createElement('video');
            video.controls = true;
            video.className = 'preview-video';
            video.preload = 'metadata';
            video.style.cssText = 'width:150px; height:150px; object-fit:cover; border-radius:10px; display:block;';
            
            const source = document.createElement('source');
            source.src = videoURL;
            source.type = videoData.type;
            
            video.appendChild(source);
            video.appendChild(document.createTextNode('お使いのブラウザは動画再生に対応していません。'));
            
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'remove-btn video-new';
            removeBtn.textContent = '×';
            
            item.appendChild(video);
            item.appendChild(removeBtn);
            $videoPreviewContainer.appendChild(item);
            
            // 動画選択ボタンを無効化
            $selectVideoBtn.disabled = true;
            $selectVideoBtn.style.opacity = '0.5';
            $selectVideoBtn.style.cursor = 'not-allowed';
        } catch (e) {
            console.error('動画の復元に失敗:', e);
            await deleteFromIndexedDB('video');
        }
    }

    // IndexedDBを初期化してから復元
    initDB().then(async () => {
        await restoreImagesFromStorage();
        await restoreVideoFromStorage();
        updateRemainingCount();
    }).catch(err => {
        console.error('IndexedDB初期化エラー:', err);
        updateRemainingCount();
    });

    // 画像選択ボタン
    $selectImageBtn.addEventListener('click', function() {
        if (getRemainingImageCount() > 0) {
            const tempInput = document.createElement('input');
            tempInput.type = 'file';
            tempInput.accept = 'image/*';
            tempInput.multiple = true;
            tempInput.style.display = 'none';
            
            tempInput.addEventListener('change', function(e) {
                handleImageSelect(e);
                document.body.removeChild(tempInput);
            });
            
            document.body.appendChild(tempInput);
            tempInput.click();
        }
    });

    // 動画選択ボタン
    $selectVideoBtn.addEventListener('click', function() {
        if (!selectedVideo) {
            const tempInput = document.createElement('input');
            tempInput.type = 'file';
            tempInput.accept = 'video/*';
            tempInput.style.display = 'none';
            
            tempInput.addEventListener('change', function(e) {
                handleVideoSelect(e);
                document.body.removeChild(tempInput);
            });
            
            document.body.appendChild(tempInput);
            tempInput.click();
        }
    });

    // 画像選択処理
    function handleImageSelect(e) {
        const files = Array.from(e.target.files);
        
        if (files.length === 0) return;
        
        console.log('選択されたファイル:', files.length);
        
        const remaining = getRemainingImageCount();
        
        if (files.length > remaining) {
            alert(`アップロードできるのはあと ${remaining} 枚までです。`);
            return;
        }

        let hasOversizedFile = false;
        files.forEach(file => {
            if (file.size > MAX_IMAGE_SIZE) {
                alert(`❌ 画像「${file.name}」のサイズが大きすぎます。\n\nファイルサイズ: ${formatFileSize(file.size)}\n上限: 2MB\n\n2MB以下の画像を選択してください。`);
                hasOversizedFile = true;
            }
        });

        if (hasOversizedFile) {
            return;
        }

        files.forEach((file, index) => {
            if (!file.type.startsWith('image/')) {
                alert('画像ファイルのみアップロードできます');
                return;
            }

            selectedFiles.push(file);
            const fileIndex = selectedFiles.length - 1;

            const reader = new FileReader();
            reader.onload = function(event) {
                const item = document.createElement('div');
                item.className = 'preview-item';
                item.dataset.fileIndex = fileIndex;
                
                const img = document.createElement('img');
                img.src = event.target.result;
                img.className = 'preview-image';
                img.style.cssText = 'width:150px; height:150px; object-fit:cover; border-radius:10px;';
                
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'remove-btn new';
                removeBtn.textContent = '×';
                removeBtn.dataset.fileIndex = fileIndex;
                
                item.appendChild(img);
                item.appendChild(removeBtn);
                $previewContainer.appendChild(item);
            };
            reader.readAsDataURL(file);
        });

        console.log('selectedFiles配列:', selectedFiles.length);
        updateRemainingCount();
        updateInputFiles();
        saveImagesToStorage();
    }

    // 動画選択処理
    function handleVideoSelect(e) {
        const file = e.target.files[0];
        if (!file) return;

        if (!file.type.startsWith('video/')) {
            alert('動画ファイルのみアップロードできます');
            return;
        }

        if (file.size > MAX_VIDEO_SIZE) {
            alert(`❌ 動画「${file.name}」のサイズが大きすぎます。\n\nファイルサイズ: ${formatFileSize(file.size)}\n上限: 10MB\n\n10MB以下の動画を選択してください。`);
            return;
        }

        selectedVideo = file;
        console.log('動画ファイルを保存:', selectedVideo.name);

        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(selectedVideo);
        $videoInput.files = dataTransfer.files;

        $videoPreviewContainer.innerHTML = '';

        const item = document.createElement('div');
        item.className = 'preview-item';
        item.style.cssText = 'width:150px; height:150px;';
        
        const videoURL = URL.createObjectURL(file);
        
        const video = document.createElement('video');
        video.controls = true;
        video.className = 'preview-video';
        video.preload = 'metadata';
        video.style.cssText = 'width:150px; height:150px; object-fit:cover; border-radius:10px; display:block;';
        
        const source = document.createElement('source');
        source.src = videoURL;
        source.type = file.type;
        
        video.appendChild(source);
        video.appendChild(document.createTextNode('お使いのブラウザは動画再生に対応していません。'));
        
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'remove-btn video-new';
        removeBtn.textContent = '×';
        
        item.appendChild(video);
        item.appendChild(removeBtn);
        $videoPreviewContainer.appendChild(item);
        
        $selectVideoBtn.disabled = true;
        $selectVideoBtn.style.opacity = '0.5';
        $selectVideoBtn.style.cursor = 'not-allowed';

        saveVideoToStorage();
    }

    // 新規画像削除
    $previewContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-btn') && e.target.classList.contains('new')) {
            e.preventDefault();
            e.stopPropagation();
            
            const item = e.target.closest('.preview-item');
            const fileIndex = parseInt(e.target.dataset.fileIndex);
            
            console.log('削除するファイルインデックス:', fileIndex);
            
            if (!isNaN(fileIndex) && fileIndex >= 0 && fileIndex < selectedFiles.length) {
                selectedFiles[fileIndex] = null;
                console.log('削除後のファイル数:', selectedFiles.filter(f => f !== null).length);
            }
            
            item.remove();
            updateRemainingCount();
            updateInputFiles();
            saveImagesToStorage();
        }
    });

    // 新規動画削除
    $videoPreviewContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-btn') && e.target.classList.contains('video-new')) {
            e.preventDefault();
            e.stopPropagation();
            
            e.target.closest('.preview-item').remove();
            
            selectedVideo = null;
            $videoInput.value = '';
            console.log('動画ファイルをクリア');
            
            $selectVideoBtn.disabled = false;
            $selectVideoBtn.style.opacity = '1';
            $selectVideoBtn.style.cursor = 'pointer';

            deleteFromIndexedDB('video');
        }
    });

    // フォーム送信時の処理
    $form.addEventListener('submit', function(e) {
        console.log('=== フォーム送信 ===');
        console.log('画像ファイル数:', $imageInput.files.length);
        console.log('動画ファイル:', $videoInput.files.length > 0 ? $videoInput.files[0].name : 'なし');
        
        const formData = new FormData(this);
        console.log('FormData内容:');
        for (let pair of formData.entries()) {
            if (pair[1] instanceof File) {
                console.log(pair[0] + ':', pair[1].name);
            } else {
                console.log(pair[0] + ':', pair[1]);
            }
        }
    });

    // 投稿成功時にIndexedDBをクリア
    if (window.location.search.includes('success') || document.referrer.includes('catpost')) {
        deleteFromIndexedDB('images').catch(() => {});
        deleteFromIndexedDB('video').catch(() => {});
    }
});