<!DOCTYPE html>
<html>
<head>
    <title>Books</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <!-- link css styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- link js functions -->
    <script src="js/app.js"></script>


</head>
<body>
    <div class="page-blur" id="page-blur">
        <div id="inputBox">
            <!-- Book search -->
            <form method="GET" action="{{ route('books') }}" name="searchForm" id="searchForm">
                @csrf
                <div class="input-container">
                    <input type="text" name="search" id="search" value="{{ $search }}" required placeholder="Search Book or Author">
                    <button type="submit" class="button primary">Search</button>
                    <button type="reset" class="button primary" onclick="clearSearch()">Clear</button>
                </div>
            </form>

            <!-- Add book -->
            <form method="POST" action="{{route('books.store')}}">
                <!-- @csrf security -->
                @csrf
                <div class="input-container">
                    <label for="title">Title <span class="required-field">*</span></label>
                    <input type="text" name="title" id="title" required>
                    <label for="author">Author <span class="required-field">*</span></label>
                    <input type="text" name="author" id="author" required>
                    <button type="submit" class="button primary">Add Book</button>
                </div>
            </form>

            <!-- Number of books shown from a search --> 
            <div id="search-number">
                @if(isset($search))
                    @if($searchCount > 0)
                        <p>{{ $searchCount }} book(s) found.</p>
                    @else
                        <p>Sorry, no results found.</p>
                    @endif
                @endif
            </div>
        </div>

        
        <!-- Book table display -->
        @if(isset($books))
            <table class="my-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Delete</th>
                        <th>Update</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($books as $book)
                    <tr>
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->author }}</td>
                        <td>
                            <!-- Delete book button -->
                            <form method="POST" action="{{ route('books.destroy', ['id' => $book->id]) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button red"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                            </form>
                        </td>
                        <td>
                            <!-- Update book button -->
                            <button onclick="openUpdateModal({{ $book->id }}, '{{ $book->author }}')" class="button blue"><i class="fa fa-repeat" aria-hidden="true"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <!-- Book sort options -->
        <div id="sort-by-container">
            <span>Sort by:</span>
            <label for="sortTitle">Title</label>
            <input type="radio" id="sortTitle" name="sortOption" value="title" onclick="sortBooks()" {{ $sortOption === 'title' ? 'checked' : '' }}>
            <label for="sortAuthor">Author</label>
            <input type="radio" id="sortAuthor" name="sortOption" value="author" onclick="sortBooks()" {{ $sortOption === 'author' ? 'checked' : '' }}>
        </div>

        <!-- export options -->
        <div id="export-container">
            <form action="{{ route('books.export') }}" method="POST">
                @csrf
                <label for="export-option">Export:</label>
                <select name="export-option" id="export-option">
                    <option value="books-xml">Books.xml</option>
                    <option value="titles-xml">Titles.xml</option>
                    <option value="authors-xml">Authors.xml</option>
                    <option value="books-csv">Books.csv</option>
                    <option value="titles-csv">Titles.csv</option>
                    <option value="authors-csv">Authors.csv</option>
                </select>
                <button type="submit">Download</button>
            </form>
        </div>
    </div>
    
    <!-- Update book input field that pops up after selecting a book to update, allowing user to change author -->
    <div class="form-popup" id="updateModal">
        <!-- ':id' is used as a placeholder value, that will be replaced by an actual book ID if it exists-->
        <form method="POST" action="{{ route('books.update', ['id' => ':id']) }}" id="updateForm">
            @csrf
            @method('PUT')
            <h3>Update Authors Name:</h3>
            <!-- This hidden field stores the ID to be sent server side for the correct book to be updated -->
            <input type="hidden" id="updateId" name="id">
            <div>
                <label for="updateAuthor">Author:</label>
                <input type="text" id="updateAuthor" name="author" required>
            </div>
            <div id="update_submit_cancel_button">
                <button type="submit" class="button primary" onclick="sumbitUpdate()">Save Changes</button>
                <button type="button" class="button primary" onclick="closeUpdateModal()">Cancel</button>
            </div>
        </form>
    </div>

<!-- This is here as a backup as stated in the README file -->
<script>
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
</script>
</body>
</html>