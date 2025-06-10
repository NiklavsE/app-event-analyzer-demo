<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppEvent extends Model
{
    protected $fillable = [
        'name',
        'context',
        'timestamp',
    ];

    protected function casts(): array
    {
        return [
            'context'   => 'array',
            'name'      => 'string',
            'timestamp' => 'datetime',
        ];
    }
}
