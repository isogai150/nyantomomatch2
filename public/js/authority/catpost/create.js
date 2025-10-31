'use strict';

document.addEventListener('DOMContentLoaded', function() {
    // DOM要素
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
    const STORAGE_KEY = 'catpost_media';

    // 状態管理
    let selectedFiles = [];
    let selectedVideo = null;

    // ========================================
    // ストレージ管理（簡潔化）
    // ========================================
    
    // 保存
    function saveToStorage() {
        const data = {
            images: selectedFiles.filter(f => f !== null).map(f => ({
                name: f.name,
                type: f.type,
                size: f.size,
                file: f
            })),
            video: selectedVideo ? {
                name: selectedVideo.name,
                type: selectedVideo.type,
                size: selectedVideo.size,
                file: selectedVideo
            } : null
        };
        
        try {
            // IndexedDBではなくメモリ上に保持（セッション内のみ有効）
            sessionStorage.setItem(STORAGE_KEY, JSON.stringify({
                imageCount: data.images.length,
                videoName: data.video?.name || null
            }));
            console.log('ストレージに保存:', data.images.length, '枚の画像');
        } catch (e) {
            console.warn('保存失敗:', e);
        }
    }

    // クリア（ブラウザリロード時・投稿成功時に実行）
    function clearStorage() {
        sessionStorage.removeItem(STORAGE_KEY);
        console.log('🗑️ ストレージをクリア');
    }

    // 初期化時の処理
    function initStorage() {
        // window.keepStorageはBladeから渡される
        // バリデーションエラー時はtrue、それ以外はfalse
        const keepStorage = window.keepStorage === true;
        
        console.log('=== 初期化 ===');
        console.log('keepStorage:', keepStorage);
        
        if (!keepStorage) {
            // 通常のページ読み込み・リロード時：ストレージをクリア
            clearStorage();
            selectedFiles = [];
            selectedVideo = null;
        }
        // バリデーションエラー時は何もしない（ファイルは残らないが、フォームの他の値はold()で復元される）
    }

    // ========================================
    // UI更新関数
    // ========================================
    
    function updateRemainingCount() {
        const remaining = MAX_IMAGES - selectedFiles.filter(f => f !== null).length;
        $remainingNumber.textContent = remaining;

        $selectImageBtn.disabled = remaining <= 0;
        $selectImageBtn.style.opacity = remaining <= 0 ? '0.5' : '1';
        $selectImageBtn.style.cursor = remaining <= 0 ? 'not-allowed' : 'pointer';
    }

    function updateInputFiles() {
        const dataTransfer = new DataTransfer();
        selectedFiles.filter(f => f !== null).forEach(file => {
            dataTransfer.items.add(file);
        });
        $imageInput.files = dataTransfer.files;
    }

    function formatFileSize(bytes) {
        return (bytes / 1024 / 1024).toFixed(2) + 'MB';
    }

    // ========================================
    // プレビュー生成関数
    // ========================================
    
    function createImagePreview(file, index) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const item = document.createElement('div');
            item.className = 'preview-item';
            item.dataset.fileIndex = index;

            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'preview-image';
            img.style.cssText = 'width:150px; height:150px; object-fit:cover; border-radius:10px;';

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'remove-btn';
            removeBtn.textContent = '×';
            removeBtn.dataset.fileIndex = index;

            item.appendChild(img);
            item.appendChild(removeBtn);
            $previewContainer.appendChild(item);
        };
        reader.readAsDataURL(file);
    }

    function createVideoPreview(file) {
        const item = document.createElement('div');
        item.className = 'preview-item';
        item.style.cssText = 'width:150px; height:150px;';

        const video = document.createElement('video');
        video.controls = true;
        video.className = 'preview-video';
        video.preload = 'metadata';
        video.style.cssText = 'width:150px; height:150px; object-fit:cover; border-radius:10px;';

        const source = document.createElement('source');
        source.src = URL.createObjectURL(file);
        source.type = file.type;

        video.appendChild(source);

        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'remove-btn video';
        removeBtn.textContent = '×';

        item.appendChild(video);
        item.appendChild(removeBtn);
        $videoPreviewContainer.innerHTML = '';
        $videoPreviewContainer.appendChild(item);

        $selectVideoBtn.disabled = true;
        $selectVideoBtn.style.opacity = '0.5';
        $selectVideoBtn.style.cursor = 'not-allowed';
    }

    // ========================================
    // ファイル選択処理
    // ========================================
    
    function handleImageSelect(files) {
        const remaining = MAX_IMAGES - selectedFiles.filter(f => f !== null).length;

        if (files.length > remaining) {
            alert(`アップロードできるのはあと ${remaining} 枚までです。`);
            return;
        }

        // サイズチェック
        for (let file of files) {
            if (file.size > MAX_IMAGE_SIZE) {
                alert(`❌ 画像「${file.name}」のサイズが大きすぎます。\n\nファイルサイズ: ${formatFileSize(file.size)}\n上限: 2MB`);
                return;
            }
            if (!file.type.startsWith('image/')) {
                alert('画像ファイルのみアップロードできます');
                return;
            }
        }

        // ファイル追加とプレビュー表示
        files.forEach(file => {
            const index = selectedFiles.length;
            selectedFiles.push(file);
            createImagePreview(file, index);
        });

        updateRemainingCount();
        updateInputFiles();
        saveToStorage();
    }

    function handleVideoSelect(file) {
        if (!file.type.startsWith('video/')) {
            alert('動画ファイルのみアップロードできます');
            return;
        }

        if (file.size > MAX_VIDEO_SIZE) {
            alert(`❌ 動画「${file.name}」のサイズが大きすぎます。\n\nファイルサイズ: ${formatFileSize(file.size)}\n上限: 10MB`);
            return;
        }

        selectedVideo = file;

        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        $videoInput.files = dataTransfer.files;

        createVideoPreview(file);
        saveToStorage();
    }

    // ========================================
    // イベントリスナー
    // ========================================
    
    // 画像選択ボタン
    $selectImageBtn.addEventListener('click', function() {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*';
        input.multiple = true;
        input.style.display = 'none';

        input.addEventListener('change', function(e) {
            handleImageSelect(Array.from(e.target.files));
            document.body.removeChild(input);
        });

        document.body.appendChild(input);
        input.click();
    });

    // 動画選択ボタン
    $selectVideoBtn.addEventListener('click', function() {
        if (selectedVideo) return;

        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'video/*';
        input.style.display = 'none';

        input.addEventListener('change', function(e) {
            if (e.target.files[0]) {
                handleVideoSelect(e.target.files[0]);
            }
            document.body.removeChild(input);
        });

        document.body.appendChild(input);
        input.click();
    });

    // 画像削除
    $previewContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-btn')) {
            e.preventDefault();
            const fileIndex = parseInt(e.target.dataset.fileIndex);
            
            if (!isNaN(fileIndex)) {
                selectedFiles[fileIndex] = null;
                e.target.closest('.preview-item').remove();
                updateRemainingCount();
                updateInputFiles();
                saveToStorage();
            }
        }
    });

    // 動画削除
    $videoPreviewContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-btn')) {
            e.preventDefault();
            selectedVideo = null;
            $videoInput.value = '';
            $videoPreviewContainer.innerHTML = '';

            $selectVideoBtn.disabled = false;
            $selectVideoBtn.style.opacity = '1';
            $selectVideoBtn.style.cursor = 'pointer';
            saveToStorage();
        }
    });

    // フォーム送信
    $form.addEventListener('submit', function(e) {
        if ($imageInput.files.length === 0) {
            e.preventDefault();
            alert('最低1枚の画像を選択してください。');
            return false;
        }
        
        // 送信成功後はストレージをクリア（リダイレクト先でクリアされる）
        console.log('フォーム送信:', $imageInput.files.length, '枚の画像');
    });

    // ========================================
    // 初期化実行
    // ========================================
    
    initStorage();
    updateRemainingCount();
});