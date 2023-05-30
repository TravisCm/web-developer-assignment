<?Php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Book;

class BookSearchTest extends TestCase
{
    use RefreshDatabase;

    public function testSearchBook()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $book1 = Book::create([
            'title' => 'Book A',
            'author' => 'John Smith',
        ]);

        $book2 = Book::create([
            'title' => 'Book B',
            'author' => 'John Smith',
        ]);


        $response = $this->get('/books?search=Book A');
        $response->assertStatus(200);
        $response->assertSee($book1->title);
    }

    public function testClearButton()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $book1 = Book::create([
            'title' => 'Book A',
            'author' => 'John Smith',
        ]);

        $book2 = Book::create([
            'title' => 'Book B',
            'author' => 'John Smith',
        ]);

        $response = $this->get('/books?search=Book A');
        $response->assertStatus(200);
        $response->assertSee($book1->title);

        // Perform clear
        $response = $this->get('/books');
        $response->assertStatus(200);

        // Assert that after the search has been cleared all books in table can be seen
        $response->assertSee($book1->title);
        $response->assertSee($book2->title);
    }
}