<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OTP extends Model
{
    use HasFactory;

    protected $quarded = ['id'];

    protected $fillable = ['code'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
