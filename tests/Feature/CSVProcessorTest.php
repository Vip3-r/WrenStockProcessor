<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test case for CSV endpoint controller.
 *
 * @package Tests\Feature
 */
class CSVProcessorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_submit_a_test_csv_into_controller()
    {
        $test_file_name = "stock.csv";
        $handle = fopen($test_file_name, "r");
        $this->post('/products/store', [
            'product' => $handle
        ])->assertOk();
    }
}
