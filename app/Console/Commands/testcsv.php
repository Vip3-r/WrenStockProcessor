<?php

namespace App\Console\Commands;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;

class testcsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testcsv {file_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test a CSV File processing given a file name.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $fileName = $this->argument('file_name');
        $file = fopen($fileName, "r");

        /*
         * File header check.
         */
        $header = true;
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

        dd($errors);
    }
}
