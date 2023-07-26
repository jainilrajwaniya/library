<?php

/**
 * * Book controller class for maintaining books
 */
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Books;
use Validator;
use App\Http\Helpers\ResponseTrait;

/**
 * Book controller class for maintaining books
 * of front end user
 */
class BooksController extends Controller {

    use ResponseTrait;

    /**
     * Add new Book
     * @param Request $request
     * @return type
     */
    public function save(Request $request)
    {
        $validations = config('api_validations.save_book');
        $validation = Validator::make($request->all(), $validations);
        if ($validation->fails()) {
            return $this->validationError($validation);
        }

        try {
            $book = Books::create([
                        "name"          => $request->name,
                        "title"         => $request->title,
                        "author"        => $request->author,
                        "copies"        => $request->copies,
                        "isbn"          => $request->isbn,
                        "published_at"  => $request->published_at
                    ]);
            
            return $this->success(['book' => $book], 'BOOK_SAVE');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
    
    /**
     * Get all books
     * @return type
     */
    public function get()
    {
        try {            
            return $this->success(['books' => Books::get()], 'BOOK_LIST');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
    
    /**
     * Update book
     * @param Request $request
     * @return type
     */
    public function edit(Request $request)
    {
        $validations = config('api_validations.edit_book');
        $validation = Validator::make(array_merge($request->all(), ['id' => $request->id]), $validations);
        if ($validation->fails()) {
            return $this->validationError($validation);
        }

        try {
            Books::where('id', $request->id)->update([
                        "isbn"          => $request->isbn,
                        "title"         => $request->title,
                        "author"        => $request->author,
                        "copies"        => $request->copies,
                        "published_at"  => $request->published_at
                    ]);
            
            return $this->success(null, 'BOOK_UPDATED');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
    
    /**
     * Delete book 
     * @param int $id
     * @return type
     */
    public function delete(int $id)
    {
        try {
            Books::where(["id" => $id])->delete();
            
            return $this->success(null, 'BOOK_DELETED');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
