<?php

namespace App\Console\Commands;

use App\Models\Quote;
use Illuminate\Console\Command;
use yananob\MyTools\Line;

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
        $target = env('MYAPP_DELIVER_TARGET');
        if (empty($target)) {
            throw new \Exception('Please specity MYAPP_DELIVER_TARGET.');
        }
        print("Sending daily-quote to {$target}\n");
        $line = new Line(base_path('config/line.json'));
        $line->sendPush(
            bot: $target,
            target: $target,
            message: Quote::randomMessage(),
        );

        return 0;
    }
}
