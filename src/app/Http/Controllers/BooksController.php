<?php

namespace App\Http\Controllers;
use App\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class BooksController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search');
        $sortOption = $request->input('sort');
    
        $query = Book::query();
        
        /** Used for book search */
        /** secure from sql injection as Laravel framework automatically performs parameter binding with curly braces surrounding search*/
        if ($search) {
            $query->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('author', 'LIKE', "%{$search}%");
        }
    
        /** Used for book sort */
        if ($sortOption === 'title') {
            $query->orderBy('title');
        } elseif ($sortOption === 'author') {
            $query->orderBy('author');
        }
    
        $books = $query->get();
        $searchCount = $books->count();

        return view('book', compact('books', 'searchCount', 'search'));
    }
    
    
    public function create()
    {
        return view('books');
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'author' => 'required',
            ]);

        /** Safe from sql injection */
        $book = new Book();
        $book->title = $validatedData['title'];
        $book->author = $validatedData['author'];
        $book->save();
        return redirect()->route('books');
    }


    public function show($id)
    {
        
    }


    public function edit($id)
    {
        $book = Book::findOrFail($id);
        return view('book', compact('book'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'author' => 'required',
        ]);
        /** uses parameterization to protect from SQL injections */
        $book = Book::findOrFail($id);
        $book->author = $request->author;
        $book->save();
    
        return redirect()->route('books');
    }


    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();
        return redirect()->route('books');
    }

    /** redirect the export request to the approprate function - xml or csv */
    public function export(Request $request) {
        $exportOption = $request->input('export-option');

        if ($exportOption === 'books-xml' || $exportOption === 'titles-xml' || $exportOption === 'authors-xml') {
            return $this->exportXml($request);
        } elseif ($exportOption === 'books-csv' || $exportOption === 'titles-csv' || $exportOption === 'authors-csv') {
            return $this->exportCsv($request);
        } 
    }

    public function exportXml(Request $request)
    {
        $exportOption = $request->input('export-option');
        $books = Book::all();

        /** Export full list of books with titles and authors as xml */
        if ($exportOption === 'books-xml') {
            $booksXmlData = new \SimpleXMLElement('<books></books>');
            foreach ($books as $book) {
                $bookElement = $booksXmlData->addChild('book');
                $bookElement->addChild('title', $book->title);
                $bookElement->addChild('author', $book->author);
            }
            $xmlContent = $booksXmlData->asXML();
            $headers = [
                'Content-Type' => 'application/xml',
                'Content-Disposition' => 'attachment; filename=books.xml',
            ];

        } else if ($exportOption === 'titles-xml') {
            /** Export only titles as xml */
            $titlesXmlData = new \SimpleXMLElement('<titles></titles>');
            foreach ($books as $book) {
                $titleElement = $titlesXmlData->addChild('title', $book->title);
            }
            $xmlContent = $titlesXmlData->asXML();
            $headers = [
                'Content-Type' => 'application/xml',
                'Content-Disposition' => 'attachment; filename=titles.xml',
            ];

        } else if ($exportOption === 'authors-xml') {
            /** Export only authors as xml */
            $authorsXmlData = new \SimpleXMLElement('<authors></authors>');
            foreach ($books as $book) {
                $authorElement = $authorsXmlData->addChild('author', $book->author);
            }
            $xmlContent = $authorsXmlData->asXML();
            $headers = [
                'Content-Type' => 'application/xml',
                'Content-Disposition' => 'attachment; filename=authors.xml',
            ];
        }

        return Response::make($xmlContent, 200, $headers);
    }


    public function exportCsv(Request $request)
    {
        $books = Book::all();
        $exportOption = $request->input('export-option');

        $filename = '';
        $csvData = [];
        $columns = [];

        if ($exportOption === 'titles-csv') {
            $filename = 'titles.csv';
            $columns = ['Title'];
            foreach ($books as $book) {
                $csvData[] = [$book->title];
            }
        } elseif ($exportOption === 'authors-csv') {
            $filename = 'authors.csv';
            $columns = ['Author'];
            foreach ($books as $book) {
                $csvData[] = [$book->author];
            }
        } elseif ($exportOption === 'books-csv') {
            $filename = 'books.csv';
            $columns = ['Title', 'Author'];
            foreach ($books as $book) {
                $csvData[] = [$book->title, $book->author];
            }
        } 

        array_unshift($csvData, $columns);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $content = $this->generateCsvContent($csvData);

        return Response::make($content, 200, $headers);
    }

    private function generateCsvContent($data)
    {
        $file = fopen('php://temp', 'w');
        foreach ($data as $row) {
            fputcsv($file, $row);
        }
        rewind($file);
        $content = stream_get_contents($file);
        fclose($file);
        return $content;
    }

}
