<?php

namespace Database\Seeders;

use App\Models\Quote;
use Illuminate\Database\Seeder;

class QuoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(0, 100) as $i) {
            Quote::create([
                "message" => "message{$i}",
                "author" => "author{$i}",
                "source" => "source{$i}",
                "source_link" => "source_link{$i}",
            ]);
        }
    }
}
