<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Http\Traits\ApiResponse;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\TryCatch;

class BookController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all books info.
        $books = Book::all();
        return $this->customeRespone(BookResource::collection($books), 'ok', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        try {
            DB::beginTransaction();
            $book = Book::create([
                'title' => $request->title,
                'author' => $request->author,
                'description' => $request->description,
                'publication_year' => $request->publication_year
            ]);
            DB::commit();
            return $this->customeRespone(new BookResource($book), 'the book created successfully', 201);
            ;
        } catch (\Throwable $th) {
            DB::rollBack();

            Log::error($th);
            return $this->customeRespone(null, 'the book not added', 400);

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        try {
            return $this->customeRespone(new BookResource($book), 'ok', 200);

        } catch (\Throwable $th) {
            return $this->customeRespone(null , 'the book not found', 404);
        }
      }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        try {
            DB::beginTransaction();
            $book->update([
                'title' => $request->title,
                'author' => $request->author,
                'description' => $request->description,
                'publication_year' => $request->publication_year
            ]);
            DB::commit();
            return $this->customeRespone(new BookResource($book), 'the book updated', 200);;
        } catch (\Throwable $th) {
            return $this->customeRespone(null, 'the book not found', 404);
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        try {
            $book->delete();
            return $this->customeRespone('', 'book deleted successfully', 200);
        } catch (\Throwable $th) {
            return $this->customeRespone(null, 'this book not found', 404);
        }

    }
}
