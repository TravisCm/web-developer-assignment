<?php

namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Book;

class DeleteBookTest extends TestCase
{
    use RefreshDatabase;

    public function testDeleteBook()
    {
        // remove the security csrf for testing purposes
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $book = Book::create([
            'title' => 'Test Book',
            'author' => 'John Smith',
        ]);

        $initialBookCount = Book::count();
        $response = $this->delete(route('books.destroy', ['id' => $book->id]));
        $response->assertStatus(302);
        $this->assertDatabaseMissing('books', [
            'id' => $book->id,
        ]);
        $this->assertEquals($initialBookCount - 1, Book::count());
        $response->assertRedirect(route('books'));
    }
}
