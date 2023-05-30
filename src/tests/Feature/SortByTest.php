<?php

namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Book;

class SortBooksTest extends TestCase
{
    use RefreshDatabase;

    public function testSortBooksByTitle()
    {
        // remove the security csrf for testing purposes
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $book1 = Book::create(['title' => 'Book C', 'author' => 'John Smith']);
        $book2 = Book::create(['title' => 'Book A', 'author' => 'Jane Doe']);
        $book3 = Book::create(['title' => 'Book B', 'author' => 'Travis Cunningham']);

        $response = $this->get(route('books', ['sort' => 'title']));
        $response->assertStatus(200);
        $response->assertSeeInOrder(['Book A', 'Book B', 'Book C']);
    }

    public function testSortBooksByAuthor()
    {
        $book1 = Book::create(['title' => 'Book A', 'author' => 'John Smith']);
        $book2 = Book::create(['title' => 'Book B', 'author' => 'Jane Doe']);
        $book3 = Book::create(['title' => 'Book C', 'author' => 'Travis Cunningham']);

        $response = $this->get(route('books', ['sort' => 'author']));
        $response->assertStatus(200);
        $response->assertSeeInOrder(['Jane Doe', 'John Smith', 'Travis Cunningham']);
    }
}
