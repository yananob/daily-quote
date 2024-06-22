<?php

namespace App\Console\Commands;

use App\Models\Quote;
use Illuminate\Console\Command;

class DeliverQuote extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:deliver-quote';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deliver daily quote with LINE.';

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
        var_dump("HELLO!");
        $quote = Quote::inRandomOrder()->first();
        var_dump($quote);
        var_dump($quote->message);
        $line = new \yananob\mytools\Line(base_path('config/line.json'));
        $line->sendMessage('nobu', 'HELLO!');

        return 0;
    }
}
