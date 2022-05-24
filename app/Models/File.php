<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $dates = ['deleted_at'];

    protected $fillable =
        [
            'user_id',
            'src',
            'ext',
            'title',
            'size',
            'type',
            'folder',
            'private',
        ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function link() {
        return $this->hasOne(FileLink::class);
    }
}
