<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessOperator extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name_kanji',
        'name_katakana',
        'contact_phone_number',
        'tag',
        'created_by',
        'updated_by'

    ];

    protected $casts = [
        'tag' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

}
