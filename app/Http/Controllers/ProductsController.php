<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductsController extends Controller
{

    public function index($id) {
        $product = Product::findOrFail($id);
        return view('products', [
            'product' => $product,
        ]);
    }

    public function create() {
        return view('products.create');
    }

    /**
     * Validates a request to process a CSV file over the network (HTTP POST) (server side).
     */
    public function store() {

        /*
         * Validate the file existence and type.
         */
        request()->validate(['product' => 'required|mimes:csv,txt']);

        /*
         * File header check.
         */
        $header = true;

        /*
         * Read the contents of the file.
         */
        $file = fopen(request()->file("product")->getRealPath(), "r");
        $insertions = 0;
        $errors = array();

        /*
         * Iterate though all of the entries.
         */
        while (($data = fgetcsv($file, 1000, ",")) !== FALSE)
        {
            /*
             * Skip the first line.
             */
            if ($header) {
                $header = false;
            } else {

                /*
                 * Preprocessing checks for the data from the file.
                 */
                $product = Product::where('strProductCode', '=', $data[0])->first();
                $stock = sizeOf($data) > 3 ? intval($data[3]) : 0;
                $price = sizeOf($data) > 4 ? doubleval($data[4]) : 0.00;
                $discontinued = sizeOf($data) > 5 ? $data[5] === "yes" : false;
                if ($stock < 10 && $price < 5 || $price > 1000) {
                    array_push($errors, "Unable to Insert " . $data[0] . ": " . ($price > 1000 ? "Overpriced." : "Insufficient stock or price."));
                    continue;
                }

                /*
                 * Valid new entry detected.
                 */
                if ($product === null) {
                    $p = Product::create([
                        'strProductCode' => $data[0],
                        'strProductName' => $data[1],
                        'strProductDesc' => $data[2],
                        'stock' => $stock,
                        'price' => $price,
                        'dtmAdded' => Carbon::now()->toDateString(),
                        'dtmDiscontinued' => $discontinued ? Carbon::now()->toDateString() : null,
                    ]);

                    /*
                     * Mark if discontinued.
                     */
                    if ($discontinued) {
                        array_push($errors, "Discontinued Product " . $data[0]);
                    }

                    $insertions++;
                } else {

                    /*
                     * Otherwise populate the errors list.
                     */
                    array_push($errors, "Duplicate entry " . $data[0]);
                }
            }
        }

        /*
         * HTTP response.
         */
        return \Response::json([
            'insertions' => $insertions,
            'errors' => $errors
        ]);
    }

}
