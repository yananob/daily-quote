<?php

namespace App\Console\Commands;

use App\Models\Quote;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeliverQuote extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:deliver-quote {target}';

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
        $target = $this->argument('target');
        Log::info("Sending daily-quote to {$target}");
        $line = new \yananob\mytools\Line(base_path('config/line.json'));
        $line->sendMessage($target, Quote::randomMessage());

        return 0;
    }
}
