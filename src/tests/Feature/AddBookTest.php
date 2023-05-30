<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Book;

class AddBookTest extends TestCase
{
    use RefreshDatabase;
    public function testAddBook()
    {
        // remove the security csrf for testing purposes
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $initialBookCount = Book::count();
        $bookData = [
            'title' => 'Test Book',
            'author' => 'John Smith',
        ];

        $response = $this->post(route('books.store'), $bookData);
        $response->assertStatus(302);
        $this->assertDatabaseHas('books', $bookData);
        $this->assertEquals($initialBookCount + 1, Book::count());
        $response->assertRedirect(route('books'));
    }
    
    public function testAddBookTitleOnly()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $initialBookCount = Book::count();
        $bookData = [
            'title' => 'Test Book',
            'author' => '', 
        ];
    
        $response = $this->post(route('books.store'), $bookData);
        $response->assertStatus(302); 
        $this->assertEquals($initialBookCount, Book::count());
    }
    
    public function testAddBookAuthorOnly()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $initialBookCount = Book::count();
        $bookData = [
            'title' => '', 
            'author' => 'John Smith',
        ];
    
        $response = $this->post(route('books.store'), $bookData);
        $response->assertStatus(302); 
        $this->assertEquals($initialBookCount, Book::count());
    }
}