<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileLink extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'file_id',
        'link'
    ];

    public function file() {
        return $this->belongsTo(File::class, 'file_id');
    }
}
