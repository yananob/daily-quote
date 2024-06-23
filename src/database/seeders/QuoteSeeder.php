<?php

namespace Database\Seeders;

use App\Models\Quote;
use Illuminate\Database\Seeder;

class QuoteSeeder extends Seeder
{
    protected const CSV_FILE = '2024-06-22_daily-quotes_quotes_quotes.csv';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fp = fopen(database_path('/seeders/data/' . self::CSV_FILE), 'r');
        // if ($fp === false) {
        //     throw new Exception("cannot open file");
        // }
        try {
            $i = 0;
            while (($line = fgetcsv($fp)) !== false) {
                if ($i++ === 0) {
                    continue;
                }

                // var_dump($line);
                Quote::create([
                    "id" => (int)$line[0] + 1,
                    "message" => $line[1],
                    "author" => $line[2],
                    "source" => $line[3],
                    "source_link" => $line[4],
                ]);
            }
        }
        finally {
            fclose($fp);
        }

        // foreach (range(0, 100) as $i) {
        //     Quote::create([
        //         "message" => "message{$i}",
        //         "author" => "author{$i}",
        //         "source" => "source{$i}",
        //         "source_link" => "source_link{$i}",
        //     ]);
        // }
    }
}
