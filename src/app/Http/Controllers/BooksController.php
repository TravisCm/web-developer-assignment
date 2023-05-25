<?php

namespace App\Http\Controllers;
use App\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sortOption = $request->input('sort');
    
        $query = Book::query();
    
        if ($search) {
            $query->where('title', 'LIKE', "%$search%")
                  ->orWhere('author', 'LIKE', "%$search%");
        }
    
        if ($sortOption === 'title') {
            $query->orderBy('title');
        } elseif ($sortOption === 'author') {
            $query->orderBy('author');
        }
    
        $books = $query->get();
        $searchCount = $books->count();

        return view('book', compact('books', 'searchCount', 'search'));
    }
    
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('books');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    $validatedData = $request->validate([
        'title' => 'required',
        'author' => 'required',
        ]);

    $book = new Book();
    $book->title = $validatedData['title'];
    $book->author = $validatedData['author'];
    $book->save();

    return redirect()->route('books');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $book = Book::findOrFail($id);
        return view('book', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'author' => 'required',
        ]);
    
        $book = Book::findOrFail($id);
        $book->author = $request->author;
        $book->save();
    
        return redirect()->route('books');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();
        return redirect()->route('books');
    }
}
