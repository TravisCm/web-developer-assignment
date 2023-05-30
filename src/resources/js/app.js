require('./bootstrap');

function openUpdateModal(id, author) {
    document.getElementById('updateModal').style.display = 'block';
    document.getElementById('updateId').value = id;
    document.getElementById('updateAuthor').value = author;
    document.getElementById('page-blur').style.filter = 'blur(5px)';
    document.getElementById('page-blur').style.backgroundColor = '#ffff';
    document.getElementById('page-blur').style.pointerEvents = 'none';
}

function closeUpdateModal() {
    document.getElementById('updateModal').style.display = 'none';
    document.getElementById('page-blur').style.filter = 'none';
    document.getElementById('page-blur').style.backgroundColor = 'transparent';
    document.getElementById('page-blur').style.pointerEvents = 'auto';
}

function sumbitUpdate() {
    var id = document.getElementById('updateId').value;
    var author = document.getElementById('updateAuthor').value;
    /* Update the form action URL with the correct book ID */
    var form = document.getElementById('updateForm');
    form.action = form.action.replace(':id', id);
    /* Set the updated author value in the hidden input field */
    document.getElementById('updateAuthor').value = author;
}

function sortBooks() {
    const sortOption = document.querySelector('input[name="sortOption"]:checked').value;
    const url = "{{ route('books') }}";
    const queryString = `?sort=${sortOption}`;
    window.location.href = url + queryString;
}

function clearSearch() {
    document.getElementById('search').value = '';
    document.querySelector('form[name="searchForm"]').submit();
}

