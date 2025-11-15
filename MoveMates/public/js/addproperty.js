
    const imageUpload = document.getElementById('imageUpload');
    const triggerUpload = document.getElementById('triggerUpload');
    const previewBox = document.getElementById('previewBox');
    const imagePreview = document.getElementById('imagePreview');
    const uploadLabel = document.getElementById('uploadLabel');

    // Open file chooser when button clicked
    triggerUpload.addEventListener('click', () => imageUpload.click());

    // Show preview once file selected
    imageUpload.addEventListener('change', function() {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          imagePreview.src = e.target.result;
          imagePreview.style.display = "block";
          uploadLabel.style.display = "none";
        }
        reader.readAsDataURL(file);
      } else {
        imagePreview.style.display = "none";
        uploadLabel.style.display = "flex";
      }
    });