<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Http\Traits\ApiResponse;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\TryCatch;

class BookController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all books info.
        $books = Book::all();

        return response()->json([
            'status' => 'success',
            'book' => $books
        ]);
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
            return response()->json([
                'status' => 'success',
                'book' => $book
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            Log::error($th);
            return response()->json([
                'status' => 'error',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return response()->json([
            'status' => 'success',
            'book' => $book
        ]);
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
            return response()->json([
                'status' => 'success',
                'book' => $book
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return response()->json([
            'status' => 'success',
        ]);

    }
}
