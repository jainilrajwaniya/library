<?php
/**
 * Checkout controller class for maintaining checkout and return of books
 */
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Checkouts;
use App\Models\Books;
use Validator;
use App\Http\Helpers\ResponseTrait;

/**
 * Checkout controller class for maintaining checkout and return of books
 */
class CheckoutController extends Controller {

    use ResponseTrait;

    /**
     * Checkout book for student
     * @param Request $request
     * @return type
     */
    public function save(Request $request)
    {
        $validations = config('api_validations.checkout');
        $validation = Validator::make($request->all(), $validations);
        if ($validation->fails()) {
            return $this->validationError($validation);
        }

        try {        
            $book = Books::where('id', $request->book_id)->first();
            //check if book is not in stock
            if($book->copies == 0) {
                throw new \Exception("OUT_OF_STOCK");
            }
            
            //check if book is already in same user's possesion
            $checkout = Checkouts::where([
                "user_id"           => $request->user_id,
                "book_id"           => $request->book_id,
                "return_date"     => null
            ])->first();
            
            if($checkout) {
                throw new \Exception("USER_ALREADY_HAVE_THIS_BOOK");
            }
        
            $checkout = Checkouts::create([
                        "user_id"          => $request->user_id,
                        "book_id"         => $request->book_id,
                        "checkout_date"  => date("Y-m-d")]);
                        
            //update book copies
            $book->copies -= 1;
            $book->save();
            
            return $this->success(['checkout' => $checkout], 'BOOK_CHECKOUT');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
    
    /**
     * Return book
     * @param int $id
     * @return type
     */
    public function returnBook(int $id)
    {
        try {
            $validations = config('api_validations.checkout_return');
            $validation = Validator::make(['id' => $id], $validations);
            if ($validation->fails()) {
                return $this->validationError($validation);
            }
                    
            $checkout = Checkouts::where('id', $id)->first();
            //check if book already returned
            if($checkout->return_date) {
                throw new \Exception('BOOK_ALREADY_RETURNED');
            }
            $checkout->return_date = date("Y-m-d");
            $checkout->save();
            
            //Add copies in books
            $book = Books::where('id', $checkout->book_id)->first();
            $book->copies += 1;
            $book->save();
                        
            return $this->success(null, 'BOOK_RETURNED');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
