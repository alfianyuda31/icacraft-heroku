<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Craft extends Model
{
    use HasFactory, SoftDeletes;

    //untuk mengisi data di field database
    protected $fillable = [
        'name', 'description', 'materials', 'price','rate','types', 'picturePath'
    ];

    //fungsi untuk merubah format tanggal dibuat ke timestamp
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }

    //fungsi untuk merubah format tanggal diubah ke timestamp
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }

    //fungsi untuk merubah camel case "picturePath" supaya terbaca di laravel
    public function toArray()
    {
        $toArray = parent::toArray();
        $toArray['picturePath'] = $this->picturePath;
        return $toArray;
    }

    //fungsi untuk mengambil gambar dari field 'picturePath' supaya tidak dibaca 'picture_path'
    public function getPicturePathAttribute()
    {
        return url('') . Storage::url($this->attributes['picturePath']);
    }
}
