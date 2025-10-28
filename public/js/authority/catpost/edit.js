'use strict';

$(function() {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    const $previewContainer = $('#preview-container');
    const $videoPreviewContainer = $('#video-preview-container');
    const $imageInput = $('#imageInput');
    const $videoInput = $('#videoInput');
    
    const $videoUploadSection = $('#video-upload-section'); // ★ 追加

    // 定数
    const MAX_IMAGE_SIZE = 2 * 1024 * 1024; // 2MB
    const MAX_VIDEO_SIZE = 10 * 1024 * 1024; // 10MB
    const MAX_IMAGES = 3;
    const MAX_VIDEOS = 1;
    let selectedFiles = [];
    let selectedVideo = null;
    let hasVideo = false;
    
    // 初期状態で既存動画があるかチェック
    if ($('#media-container .preview-video').length > 0) {
        hasVideo = true;
        $videoInput.prop('disabled', true);
    }
    
    // ★★★ 画像・動画ボタンの状態を更新する関数 ★★★
    function updateButtonStates() {
        const remaining = getRemainingImageCount();
        
        console.log('ボタン状態更新 - 残り画像枚数:', remaining);
        
        if (remaining > 0) {
            $imageInput.prop('disabled', false);
            $('#selectImageBtn').prop('disabled', false);
            console.log('画像ボタンを有効化');
        } else {
            $imageInput.prop('disabled', true);
            $('#selectImageBtn').prop('disabled', true);
            console.log('画像ボタンを無効化');
        }
        
        // 動画ボタンの状態
        if (hasVideo || selectedVideo) {
            $videoInput.prop('disabled', true);
            $('#selectVideoBtn').prop('disabled', true);
        } else {
            $videoInput.prop('disabled', false);
            $('#selectVideoBtn').prop('disabled', false);
        }
    }
    
    // ★★★ 画像選択ボタンのクリックイベント ★★★
    $('#selectImageBtn').on('click', function() {
        const remaining = getRemainingImageCount();
        
        // 画像が既に上限に達している場合はアラート表示
        if (remaining <= 0) {
            alert(`画像は最大${MAX_IMAGES}枚までしか追加できません。`);
            return;
        }
        
        $imageInput.click();
    });
    
    // ★★★ 動画選択ボタンのクリックイベント ★★★
    $('#selectVideoBtn').on('click', function() {
        // 既に動画が存在する場合はアラート表示
        if (hasVideo || selectedVideo) {
            alert(`動画は最大${MAX_VIDEOS}本までしか追加できません。`);
            return;
        }
        
        $videoInput.click();
    });
    
    // ファイルサイズをフォーマット
    function formatFileSize(bytes) {
        return (bytes / 1024 / 1024).toFixed(2) + 'MB';
    }
    
    // 残り画像枚数を計算
    function getRemainingImageCount() {
        const existingImages = $('#media-container img.preview-image').length;
        const newImages = selectedFiles.length;
        return MAX_IMAGES - existingImages - newImages;
    }
    
    // ★★★ 画像の合計枚数をチェック ★★★
    function getTotalImageCount() {
        const existingImages = $('#media-container img.preview-image').length;
        const newImages = selectedFiles.length;
        return existingImages + newImages;
    }
    
    // フォーム送信処理
    $('form').on('submit', function(e) {
        console.log('フォームが送信されます');
        
        // ★★★ 画像が1枚もない場合はエラー ★★★
        const totalImages = getTotalImageCount();
        if (totalImages === 0) {
            e.preventDefault();
            alert('最低1枚の画像を選択してください。');
            return false;
        }
        
        console.log('selectedFiles:', selectedFiles.length);
        console.log('selectedVideo:', selectedVideo ? selectedVideo.name : 'なし');
        console.log('総画像数:', totalImages);
        
        // 新規画像または新規動画がある場合
        if (selectedFiles.length > 0 || selectedVideo) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.set('_method', 'PUT');
            
            // 既存のimage[]を削除
            formData.delete('image[]');
            
            // 新規画像を「images[]」で追加
            selectedFiles.forEach((file, index) => {
                console.log(`画像${index + 1}を追加:`, file.name);
                formData.append('images[]', file);
            });
            
            // 新規動画を追加
            if (selectedVideo) {
                formData.delete('video');
                formData.append('video', selectedVideo);
                console.log('動画を追加:', selectedVideo.name);
            }
            
            console.log('送信するFormDataの内容:');
            for (let pair of formData.entries()) {
                if (pair[1] instanceof File) {
                    console.log(pair[0] + ': ' + pair[1].name + ' (' + pair[1].size + ' bytes)');
                } else {
                    console.log(pair[0] + ': ' + pair[1]);
                }
            }
            
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    console.log('更新成功');
                    window.location.href = '/my/catpost';
                },
                error: function(xhr) {
                    console.error('更新失敗:', xhr.status);
                    console.error('エラー内容:', xhr.responseText);
                    
                    // 既存のエラーメッセージを削除
                    $('.alert-danger').remove();
                    
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        
                        // 各フィールドにエラーメッセージを表示
                        for (let key in errors) {
                            const messages = errors[key];
                            let $targetElement = null;
                            
                            // フィールド名に応じて表示位置を特定
                            if (key === 'images' || key === 'images.0' || key === 'images.1' || key === 'images.2') {
                                $targetElement = $('#imageInput');
                            } else if (key === 'video') {
                                $targetElement = $('#videoInput');
                            } else {
                                $targetElement = $(`[name="${key}"]`);
                            }
                            
                            if ($targetElement && $targetElement.length > 0) {
                                messages.forEach(message => {
                                    const $errorDiv = $('<div class="alert-danger"></div>').text(message);
                                    $targetElement.after($errorDiv);
                                });
                            }
                        }
                        
                        // ページ最上部にスクロール
                        $('html, body').animate({ scrollTop: 0 }, 300);
                    } else {
                        // その他のエラーの場合
                        const $errorDiv = $('<div class="alert-danger" style="text-align: center; margin: 20px 0;"></div>').text('更新に失敗しました。もう一度お試しください。');
                        $('form').prepend($errorDiv);
                        $('html, body').animate({ scrollTop: 0 }, 300);
                    }
                }
            });
            
            return false;
        }
        
        return true;
    });
    
    // 既存メディア削除（Ajax）
    $(document).on('click', '.remove-btn[data-type]', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const $btn = $(this);
        const type = $btn.data('type');
        const id = $btn.data('id');
        
        // ★★★ 最後の画像を削除しようとしている場合は警告 ★★★
        if (type === 'image') {
            const remainingExistingImages = $('#media-container img.preview-image').length - 1;
            const newImages = selectedFiles.length;
            if (remainingExistingImages + newImages === 0) {
                alert('最低1枚の画像が必要です。新しい画像を追加してから削除してください。');
                return;
            }
        }
        
        if (!confirm(`この${type === 'image' ? '画像' : '動画'}を削除しますか？`)) return;
        
        $.ajax({
            url: `/catpost/media/${type}/${id}`,
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            success: function(res) {
                if (res.success) {
                    $btn.closest('.preview-item').remove();
                    
                    if (type === 'video') {
                        hasVideo = false;
                        $videoInput.prop('disabled', false);
                    }
                    
                    // ★★★ 削除後にボタンの状態を更新 ★★★
                    updateButtonStates();
                } else {
                    alert('削除に失敗しました');
                }
            },
            error: function() { 
                alert('通信エラーが発生しました'); 
            }
        });
    });
    
    // 画像プレビュー（複数選択対応）
    $imageInput.on('change', function(e) {
        const files = Array.from(e.target.files);
        
        console.log('選択されたファイル:', files.length);
        
        const remaining = getRemainingImageCount();
        
        if (files.length > remaining) {
            alert(`アップロードできるのはあと ${remaining} 枚までです。`);
            $(this).val('');
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
            $(this).val('');
            return;
        }
        
        selectedFiles = selectedFiles.concat(files);
        console.log('selectedFiles配列:', selectedFiles.length);
        
        files.forEach((file, index) => {
            if (!file.type.startsWith('image/')) {
                alert('画像ファイルのみアップロードできます');
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(event) {
                const $item = $('<div class="preview-item"></div>');
                $item.append(`
                    <img src="${event.target.result}" 
                         class="preview-image" 
                         style="width:150px; height:150px; object-fit:cover; border-radius:10px;">
                `);
                
                const fileIndex = selectedFiles.length - files.length + index;
                $item.append(`<button type="button" class="remove-btn new" data-file-index="${fileIndex}">×</button>`);
                
                $previewContainer.append($item);
            };
            reader.readAsDataURL(file);
        });
        
        // inputの値をクリア（同じファイルを再選択できるようにする）
        $(this).val('');
        
        // ★★★ 画像追加後にボタンの状態を更新 ★★★
        updateButtonStates();
    });
    
    // 動画プレビュー
    $videoInput.on('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        
        if (!file.type.startsWith('video/')) {
            alert('動画ファイルのみアップロードできます');
            $(this).val('');
            return;
        }
        
        if (file.size > MAX_VIDEO_SIZE) {
            alert(`❌ 動画「${file.name}」のサイズが大きすぎます。\n\nファイルサイズ: ${formatFileSize(file.size)}\n上限: 10MB\n\n10MB以下の動画を選択してください。`);
            $(this).val('');
            return;
        }
        
        // 選択された動画ファイルを保存
        selectedVideo = file;
        console.log('動画ファイルを保存:', selectedVideo.name);
        
        // 既存の動画プレビューをクリア
        $videoPreviewContainer.empty();
        
        const $item = $('<div class="preview-item" style="width:150px; height:150px;"></div>');
        const videoURL = URL.createObjectURL(file);
        
        $item.append(`
            <video controls class="preview-video" preload="metadata" style="width:150px; height:150px; object-fit:cover; border-radius:10px; display:block;">
                <source src="${videoURL}" type="${file.type}">
                お使いのブラウザは動画再生に対応していません。
            </video>
        `);
        $item.append(`<button type="button" class="remove-btn video-new">×</button>`);
        
        $videoPreviewContainer.append($item);
        
        hasVideo = true;
        
        // ★★★ 動画追加後にボタンの状態を更新 ★★★
        updateButtonStates();
    });
    
    // 新規画像削除（送信前）
    $previewContainer.on('click', '.remove-btn.new', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const $item = $(this).closest('.preview-item');
        const fileIndex = parseInt($(this).data('file-index'));
        
        console.log('削除するファイルインデックス:', fileIndex);
        
        if (!isNaN(fileIndex) && fileIndex >= 0 && fileIndex < selectedFiles.length) {
            selectedFiles.splice(fileIndex, 1);
            console.log('削除後のファイル数:', selectedFiles.length);
            
            // インデックスを再調整
            $previewContainer.find('.remove-btn.new').each(function(i) {
                $(this).attr('data-file-index', i);
            });
        }
        
        $item.remove();
        
        // ★★★ 画像削除後にボタンの状態を更新 ★★★
        updateButtonStates();
    });
    
    // 新規動画削除
    $(document).on('click', '.remove-btn.video-new', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        $(this).closest('.preview-item').remove();
        $videoInput.val('');
        
        // 選択された動画ファイルをクリア
        selectedVideo = null;
        console.log('動画ファイルをクリア');
        
        hasVideo = false;
        
        // ★★★ 動画削除後にボタンの状態を更新 ★★★
        updateButtonStates();
    });
});