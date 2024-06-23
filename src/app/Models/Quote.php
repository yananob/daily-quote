<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'message', 'author', 'source', 'source_link'];

    public static function randomMessage(): string
    {
        $quote = self::inRandomOrder()->first();

        return <<<EOF
Quote of the day #{$quote->id}:

{$quote->message}

[{$quote->author}] {$quote->source} {$quote->source_link}
EOF;
    }

}
