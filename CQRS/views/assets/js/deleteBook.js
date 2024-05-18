async function deleteBook(button) {
  var bookId = button.getAttribute('data-book-id');
  var tableFiled = document.getElementById('book-' + bookId);

  tableFiled.style.display = 'none';

  try {
    const response = await fetch('/admin/book/' + bookId, {
      method: 'DELETE'
    });

    if (response.ok) {
      const responseData = await response.json();
      console.log(responseData)
      var deletedElement = document.getElementById('book-' + bookId);
      if (deletedElement) {
        deletedElement.remove();
      }
    } else {
      console.error('Ошибка при удалении книги:', response.statusText);
    }
  } catch (error) {
    console.error('Ошибка при удалении книги:', error);
  }
}