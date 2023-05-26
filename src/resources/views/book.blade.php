<!DOCTYPE html>
<html>
<head>
    <title>Books</title>
</head>
<body>

    <!-- Add book -->
    <form method="POST" action="{{route('books.store')}}">
        <!-- @csrf security -->
        @csrf
        <div>
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" required>
        </div>
        <div>
            <label for="author">Author:</label>
            <input type="text" name="author" id="author" required>
        </div>
        <button type="submit">Add Book</button>
    </form>

    <!-- Book search -->
    <form method="GET" action="{{ route('books') }}" name="searchForm">
        <div>
            <label for="search">Search:</label>
            <input type="text" name="search" id="search" value="{{ $search }}" required>
        </div>
        <button type="submit">Search</button>
        <button type="button" onclick="clearSearch()">Clear</button>
    </form>

    <!-- Number of books shown from a search --> 
    @if(isset($search))
        @if($searchCount > 0)
            <p>{{ $searchCount }} book(s) found.</p>
        @else
            <p>Sorry, no results found.</p>
        @endif
    @endif
    
    <!-- Book table display -->
    @if(isset($books))
        <table>
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
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                    <td>
                        <!-- Update book button -->
                        <button onclick="openUpdateModal({{ $book->id }}, '{{ $book->author }}')">Update</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Book sort options -->
    <div>
        <span>Sort by:</span>
        <label for="sortTitle">Title</label>
        <input type="radio" id="sortTitle" name="sortOption" value="title" onclick="sortBooks()">
        <label for="sortAuthor">Author</label>
        <input type="radio" id="sortAuthor" name="sortOption" value="author" onclick="sortBooks()">
    </div>

    <!-- Update book input field that pops up after selecting a book to update, allowing user to change author -->
    <div id="updateModal" style="display: none;">
        <!-- ':id' is used as a placeholder value, that will be replaced by an actual book ID if it exists-->
        <form method="POST" action="{{ route('books.update', ['id' => ':id']) }}">
            @csrf
            @method('PUT')
            <!-- This hidden field stores the ID to be sent server side for the correct book to be updated -->
            <input type="hidden" id="updateId" name="id">
            <div>
                <label for="updateAuthor">Author:</label>
                <input type="text" id="updateAuthor" name="author" required>
            </div>
            <button type="submit">Update</button>
            <button type="button" onclick="closeUpdateModal()">Cancel</button>
        </form>
    </div>

    <!-- export options -->
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

<script>

    function openUpdateModal(id, author) {
        document.getElementById('updateModal').style.display = 'block';
        document.getElementById('updateId').value = id;
        document.getElementById('updateAuthor').value = author;
    }

    function closeUpdateModal() {
        document.getElementById('updateModal').style.display = 'none';
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