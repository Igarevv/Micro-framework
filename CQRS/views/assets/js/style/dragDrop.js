let fileInput = document.getElementById('fileInput');
let dragAndDrop = document.querySelector(".upload-dropzone");
let fileList = document.getElementById('fileList');
let uploadButton = document.getElementById("uploadBtn");
let clearBtn = document.getElementById('clearBtn');

document.getElementById('dropzone').addEventListener('click', function() {
  fileInput.click();
});

const types = ['text/csv', 'application/csv'];
let csvFiles = [];

dragAndDrop.addEventListener('dragenter', (e) => {
  e.preventDefault();
  dragAndDrop.classList.add('active');
});

dragAndDrop.addEventListener('dragleave', (e) => {
  e.preventDefault();
  dragAndDrop.classList.remove('active');
});

dragAndDrop.addEventListener('dragover', (e) => {
  e.preventDefault();
});

dragAndDrop.addEventListener('drop', (e) => {
  e.preventDefault();
  dragAndDrop.classList.remove('active');

  let files = e.dataTransfer.files;

  for (let file in files){
    if (! types.includes(files[file].type)){
      continue;
    }
    csvFiles.push(files[file]);
    displayFile(csvFiles[file]);
  }

  if (csvFiles.length > 0){
    uploadButton.classList.replace('secondary', 'primary');
    uploadButton.removeAttribute('disabled');
    clearBtn.removeAttribute('disabled');
  }

  let dataTransfer = new DataTransfer();
  csvFiles.forEach(file => {
    dataTransfer.items.add(file);
  });

  fileInput.files = dataTransfer.files;

});

fileInput.addEventListener('change', (e) => {
  let fileData = e.target.files;

  for (let file in fileData){
    if (! types.includes(fileData[file].type)){
      continue;
    }
    csvFiles.push(fileData[file]);
    displayFile(csvFiles[file]);
  }

  if (csvFiles.length > 0){
    uploadButton.classList.replace('secondary', 'primary');
    uploadButton.removeAttribute('disabled');
    clearBtn.removeAttribute('disabled');
  }

  let dataTransfer = new DataTransfer();
  csvFiles.forEach(file => {
    dataTransfer.items.add(file);
  });

  fileInput.files = dataTransfer.files;
});

function displayFile(file) {
  let fileItem = document.createElement('div');
  fileItem.classList.add('file-item');

  let fileIcon = document.createElement('span');
  fileIcon.classList.add('file-icon');
  fileIcon.textContent = '📄';

  let fileName = document.createElement('span');
  fileName.classList.add('file-name');
  fileName.textContent = file.name;

  fileItem.appendChild(fileIcon);
  fileItem.appendChild(fileName);
  fileList.appendChild(fileItem);
}

clearBtn.addEventListener('click', () => {
  csvFiles = [];

  fileList.innerHTML = '';

  uploadButton.classList.replace('primary', 'secondary');
  uploadButton.setAttribute('disabled', 'disabled');
  clearBtn.setAttribute('disabled', 'disabled');
});

