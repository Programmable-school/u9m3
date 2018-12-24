<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timestamps extends Model
{
    protected $fillable = ['user_id', 'punchIn', 'punchOut'];

    public function user()
    {
        $this->belongsTo(User::class);
    }
}
