<!DOCTYPE html>
<html>
<head>
    <title>Books</title>
</head>
<body>
    <form method="POST" action="">
        <!-- small amount of security -->
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
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($books as $book)
            <tr>
                <td>{{ $book->title }}</td>
                <td>{{ $book->author }}</td>
                <td>
                    <form method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>