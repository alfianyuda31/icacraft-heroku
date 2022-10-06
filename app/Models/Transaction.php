<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    //untuk mengisi data di field database
    protected $fillable = [
        'craft_id', 'user_id', 'quantity', 'total','status','payment_url'
    ];

    //fungsi relasi database tabel transaksi dengan tabel craft
    public function craft()
    {
        return $this->hasOne(Craft::class, 'id', 'craft_id');
    }

    //fungsi relasi database tabel transaksi dengan tabel user
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

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
}
