<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class BookAuthorCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_authors_and_search(): void
    {
        $author1 = Author::factory()->create(['name' => 'Jane Austen']);
        $author2 = Author::factory()->create(['name' => 'Charles Dickens']);

        $response = $this->get(route('authors.index'));
        $response->assertStatus(200);
        $response->assertSee('Jane Austen');
        $response->assertSee('Charles Dickens');

        // Search test
        $searchResponse = $this->get(route('authors.index', ['search' => 'Austen']));
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('Jane Austen');
        $searchResponse->assertDontSee('Charles Dickens');
    }

    public function test_can_create_author(): void
    {
        $response = $this->post(route('authors.store'), [
            'name' => 'George Orwell',
            'birth_date' => '1903-06-25',
        ]);

        $response->assertRedirect(route('authors.index'));
        $this->assertDatabaseHas('authors', [
            'name' => 'George Orwell',
        ]);
        $author = Author::where('name', 'George Orwell')->firstOrFail();
        $this->assertEquals('1903-06-25', $author->birth_date->format('Y-m-d'));
    }

    public function test_can_create_author_via_ajax(): void
    {
        $response = $this->postJson(route('authors.store'), [
            'name' => 'Leo Tolstoy',
            'birth_date' => '1828-09-09',
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'data' => [
                'name' => 'Leo Tolstoy',
            ],
        ]);
        $this->assertDatabaseHas('authors', ['name' => 'Leo Tolstoy']);
    }

    public function test_author_validation_rules(): void
    {
        $response = $this->postJson(route('authors.store'), [
            'name' => '',
            'birth_date' => 'invalid-date',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'birth_date']);
    }

    public function test_can_update_author(): void
    {
        $author = Author::factory()->create(['name' => 'Old Name']);

        $response = $this->put(route('authors.update', $author), [
            'name' => 'Updated Author Name',
            'birth_date' => '1950-01-01',
        ]);

        $response->assertRedirect(route('authors.index'));
        $this->assertDatabaseHas('authors', ['id' => $author->id, 'name' => 'Updated Author Name']);
    }

    public function test_can_delete_author_and_cascades_books(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->id]);

        $response = $this->delete(route('authors.destroy', $author));

        $response->assertRedirect(route('authors.index'));
        $this->assertDatabaseMissing('authors', ['id' => $author->id]);
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    public function test_can_list_books_and_search(): void
    {
        $author = Author::factory()->create(['name' => 'Arthur Conan Doyle']);
        $book1 = Book::factory()->create(['title' => 'A Study in Scarlet', 'author_id' => $author->id]);
        $book2 = Book::factory()->create(['title' => 'The Sign of the Four', 'author_id' => $author->id]);

        $response = $this->get(route('books.index'));
        $response->assertStatus(200);
        $response->assertSee('A Study in Scarlet');
        $response->assertSee('The Sign of the Four');

        // Search test
        $searchResponse = $this->get(route('books.index', ['search' => 'Scarlet']));
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('A Study in Scarlet');
        $searchResponse->assertDontSee('The Sign of the Four');
    }

    public function test_can_create_book(): void
    {
        $author = Author::factory()->create();

        $response = $this->post(route('books.store'), [
            'title' => 'Nineteen Eighty-Four',
            'author_id' => $author->id,
            'published_date' => '1949-06-08',
        ]);

        $response->assertRedirect(route('books.index'));
        $this->assertDatabaseHas('books', [
            'title' => 'Nineteen Eighty-Four',
            'author_id' => $author->id,
        ]);
        $book = Book::where('title', 'Nineteen Eighty-Four')->firstOrFail();
        $this->assertEquals('1949-06-08', $book->published_date->format('Y-m-d'));
    }

    public function test_can_create_book_via_ajax(): void
    {
        $author = Author::factory()->create();

        $response = $this->postJson(route('books.store'), [
            'title' => 'Animal Farm',
            'author_id' => $author->id,
            'published_date' => '1945-08-17',
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'data' => [
                'title' => 'Animal Farm',
            ],
        ]);
        $this->assertDatabaseHas('books', ['title' => 'Animal Farm']);
    }

    public function test_book_validation_rules(): void
    {
        $response = $this->postJson(route('books.store'), [
            'title' => '',
            'author_id' => 999999, // Non-existent author
            'published_date' => 'not-a-date',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title', 'author_id', 'published_date']);
    }

    public function test_can_update_book(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->id]);

        $response = $this->put(route('books.update', $book), [
            'title' => 'Updated Title',
            'author_id' => $author->id,
            'published_date' => '2020-01-01',
        ]);

        $response->assertRedirect(route('books.index'));
        $this->assertDatabaseHas('books', ['id' => $book->id, 'title' => 'Updated Title']);
    }

    public function test_can_delete_book_via_ajax(): void
    {
        $book = Book::factory()->create();

        $response = $this->deleteJson(route('books.destroy', $book));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    public function test_author_show_page_displays_author_and_books(): void
    {
        $author = Author::factory()->create(['name' => 'F. Scott Fitzgerald']);
        $book = Book::factory()->create([
            'title' => 'The Great Gatsby',
            'author_id' => $author->id,
        ]);

        $response = $this->get(route('authors.show', $author));

        $response->assertStatus(200);
        $response->assertSee('F. Scott Fitzgerald');
        $response->assertSee('The Great Gatsby');
    }

    public function test_book_show_page_displays_book_and_author(): void
    {
        $author = Author::factory()->create(['name' => 'Herman Melville']);
        $book = Book::factory()->create([
            'title' => 'Moby Dick',
            'author_id' => $author->id,
        ]);

        $response = $this->get(route('books.show', $book));

        $response->assertStatus(200);
        $response->assertSee('Moby Dick');
        $response->assertSee('Herman Melville');
    }
}
