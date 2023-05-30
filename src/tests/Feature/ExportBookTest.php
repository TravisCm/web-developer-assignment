<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Book;

class ExportBooksTest extends TestCase
{
    use RefreshDatabase;
    protected function createBooks()
    {
        $book1 = Book::create(['title' => 'Book A', 'author' => 'Author X']);
        $book2 = Book::create(['title' => 'Book B', 'author' => 'Author Y']);
        $book3 = Book::create(['title' => 'Book C', 'author' => 'Author Z']);
    }

    public function testExportBooksXML()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $this->createBooks();

        $response = $this->post(route('books.export'), ['export-option' => 'books-xml']);
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');
    }

    public function testExportTitlesXML()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $this->createBooks();

        $response = $this->post(route('books.export'), ['export-option' => 'titles-xml']);
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');
    }

    public function testExportAuthorsXML()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $this->createBooks();

        $response = $this->post(route('books.export'), ['export-option' => 'authors-xml']);
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');
    }

    public function testExportBooksCSV()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $this->createBooks();

        $response = $this->post(route('books.export'), ['export-option' => 'books-csv']);
        $response->assertStatus(200);
        // Use regular expression to match the content type as laravel automatically will add "charset=UTF-8'" 
        // to the end of content type. The reg exp used to check if the Content-Type header starts with "text/csv"
        $this->assertTrue(preg_match('/^text\/csv/', $response->headers->get('Content-Type')) === 1);
    }

    public function testExportTitlesCSV()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $this->createBooks();

        $response = $this->post(route('books.export'), ['export-option' => 'titles-csv']);
        $response->assertStatus(200);
        $this->assertTrue(preg_match('/^text\/csv/', $response->headers->get('Content-Type')) === 1);
    }

    public function testExportAuthorsCSV()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $this->createBooks();

        $response = $this->post(route('books.export'), ['export-option' => 'authors-csv']);
        $response->assertStatus(200);
        $this->assertTrue(preg_match('/^text\/csv/', $response->headers->get('Content-Type')) === 1);

    }
}
