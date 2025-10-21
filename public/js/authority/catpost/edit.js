document.addEventListener('DOMContentLoaded', function() {
  const imageInput = document.getElementById('image');
  if (!imageInput) return;

  const maxFiles = parseInt(imageInput.dataset.maxFiles);

  imageInput.addEventListener('change', function(e) {
    if (this.files.length > maxFiles) {
      alert(`画像はあと ${maxFiles} 枚までしか追加できません。`);
      this.value = ''; // 選択をリセット
    }
  });
});