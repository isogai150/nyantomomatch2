$(function() {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    const $remainingCount = $('#remaining-count');
    const $previewContainer = $('#preview-container');
    let remaining = parseInt($remainingCount.text(), 10);

    function updateRemaining(delta) {
        remaining += delta;
        if (remaining < 0) remaining = 0;
        $remainingCount.text(remaining);
    }

    // ★★★ フォーム送信を許可（追加） ★★★
    $('form').on('submit', function(e) {
        // バリデーションチェック（必要に応じて）
        console.log('フォームが送信されます');
        // e.preventDefault(); があったら削除すること！
        // 何もしないでフォーム送信を通す
        return true;
    });

    // 既存メディア削除（Ajax）
    $(document).on('click', '.remove-btn[data-type]', function(e) {
        e.preventDefault(); // ★ ここは重要：フォーム送信を防ぐ
        e.stopPropagation(); // ★ イベントの伝播も止める
        
        const $btn = $(this);
        const type = $btn.data('type');
        const id = $btn.data('id');

        if (!confirm(`この${type === 'image' ? '画像' : '動画'}を削除しますか？`)) return;

        $.ajax({
            url: `/catpost/media/${type}/${id}`,
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            success: function(res) {
                if (res.success) {
                    $btn.closest('.preview-item').remove();
                    if (type === 'image') {
                        updateRemaining(1);
                    }
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
    $('#imageInput').on('change', function(e) {
        const files = Array.from(e.target.files);
        
        // 残り枚数チェック
        if (files.length > remaining) {
            alert(`アップロードできるのはあと ${remaining} 枚までです。`);
            $(this).val('');
            return;
        }

        // 各ファイルをプレビュー表示
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
                         style="width:150px; border-radius:8px;">
                `);
                
                // 削除ボタン（新規追加用）
                $item.append(`<button type="button" class="remove-btn new" data-index="${index}">×</button>`);
                
                $previewContainer.append($item);
            };
            reader.readAsDataURL(file);
        });

        updateRemaining(-files.length);
    });

    // 動画プレビュー
    $('#videoInput').on('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        if (!file.type.startsWith('video/')) {
            alert('動画ファイルのみアップロードできます');
            $(this).val('');
            return;
        }

        // 動画プレビューエリアをクリア
        $('#video-preview-container').empty();

        const $item = $('<div class="preview-item"></div>');
        const videoURL = URL.createObjectURL(file);
        $item.append(`
            <video width="150" controls>
                <source src="${videoURL}" type="${file.type}">
            </video>
        `);
        $item.append(`<button type="button" class="remove-btn video-new">×</button>`);
        
        $('#video-preview-container').append($item);
    });

    // 新規メディア削除（送信前）
    $previewContainer.on('click', '.remove-btn.new', function(e) {
        e.preventDefault(); // ★ 追加
        e.stopPropagation(); // ★ 追加
        
        const $item = $(this).closest('.preview-item');
        $item.remove();
        updateRemaining(1);
        
        // input要素をリセット
        $('#imageInput').val('');
    });

    // 新規動画削除
    $(document).on('click', '.remove-btn.video-new', function(e) {
        e.preventDefault(); // ★ 追加
        e.stopPropagation(); // ★ 追加
        
        $(this).closest('.preview-item').remove();
        $('#videoInput').val('');
    });
});