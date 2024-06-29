<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    use HasFactory;

    protected $table = 'dokumen';

    protected $fillable = ['nama_dokumen', 'file_dokumen', 'upload_by'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'upload_by', 'id');
    }
}
