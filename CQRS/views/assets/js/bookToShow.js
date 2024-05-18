document.addEventListener('DOMContentLoaded', () => {
  const selectElement = document.getElementById('book-to-show');
  const pageId =selectElement.getAttribute('data-page-id');

  selectElement.addEventListener('change', (event) => {
    const selectedOption = event.target.value;

    const url = `?page=${pageId}&show=${selectedOption}`;

    fetch(url)
      .then(response => {
        if (! response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      }).then(data => {

        console.log('Received data:', data);

      }).catch(error => {
        console.error('Fetch error:', error);
      });
  });
});
