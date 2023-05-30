<?php

namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Book;

class UpdateBookAuthorTest extends TestCase
{
    use RefreshDatabase;

    public function testUpdateBookAuthor()
    {
        // remove the security csrf for testing purposes
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $book = Book::create([
            'title' => 'Test Book',
            'author' => 'John Smith',
        ]);

        $newAuthor = 'Jane Doe';

        $response = $this->put(route('books.update', ['id' => $book->id]), [
            'author' => $newAuthor,
        ]);
        $response->assertStatus(302);
        $book->refresh();
        $this->assertEquals($newAuthor, $book->author);
        $response->assertRedirect(route('books'));
    }
}







