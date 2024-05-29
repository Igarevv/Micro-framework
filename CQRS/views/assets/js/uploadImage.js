let uploadBtn = document.getElementById('upload');
let clearBtn = document.getElementById('clear');
let noImage = document.getElementById('noImage');
let fileInput = document.getElementById('exampleFormControlFile1');
let imagePreview = document.querySelector('.image-preview');
let loader = document.getElementById('loading-overlay');
let modal = document.getElementById('staticBackdrop');

let bookId;
const addEventListeners = () => {
  document.querySelectorAll('button[data-target="#staticBackdrop"]').forEach((button) => {
    button.addEventListener('click', () => {
      bookId = button.getAttribute('data-book-id');
      document.getElementById('book-id').innerHTML = bookId;
    });
  });
}

const table = document.getElementById('table');
const observer = new MutationObserver(addEventListeners);
observer.observe(table, { childList: true, subtree: true });

addEventListeners();

fileInput.addEventListener('change', (e) => {
  let allowedExt = ['image/png', 'image/jpg', 'image/jpeg'];

  let image = e.target.files[0];

  if (image && allowedExt.includes(image.type)) {
    let tmpImageUrl = URL.createObjectURL(image);
    imagePreview.innerHTML += `<img src="${tmpImageUrl}" class="image-p" alt="preview">`;

    noImage.hidden = true;
    uploadBtn.disabled = false;
    clearBtn.disabled = false;
  } else {
    uploadBtn.disabled = true;
    noImage.hidden = false;
  }
  clearBtn.disabled = false;
});

clearBtn.addEventListener('click', () => {
  fileInput.value = '';
  imagePreview.innerHTML = '';
  noImage.hidden = false;
  uploadBtn.disabled = true;
  clearBtn.disabled = true;
});

uploadBtn.addEventListener('click', () => {
  let file = fileInput.files[0];

  let formData = new FormData();
  formData.append('image', file);
  formData.append('bookId', bookId);

  let xhr = new XMLHttpRequest();

  loader.style.display = 'flex';
  modal.style.display = 'none';

  xhr.open('POST', '/admin/book/unready');
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE){
      loader.style.display = 'none';

      if (xhr.status === 200){
        let response = JSON.parse(xhr.responseText);

        if (response.redirect){
          window.location.href = response.redirect;
        }
      }
    }
  }
  xhr.send(formData);
});