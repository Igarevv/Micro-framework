window.onload = function() {
  checkAndUpdateURL();
};

window.onpopstate = function(event) {
  checkAndUpdateURL();
};

function checkAndUpdateURL() {
  let currentURL = window.location.href;
  let correctURL = document.getElementById('hidden').getAttribute('data-tag');
  if (currentURL !== correctURL) {
    window.history.replaceState(null, null, correctURL);
  }
}