async function deleteBook(button) {
  let bookId = button.getAttribute('data-book-id');
  let tableFiled = document.getElementById('book-' + bookId);

  tableFiled.style.display = 'none';

  try {
    const response = await fetch('/admin/book/' + bookId, {
      method: 'DELETE'
    });

    if (response.ok) {
      const responseData = await response.json();
      let deletedElement = document.getElementById('book-' + bookId);
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