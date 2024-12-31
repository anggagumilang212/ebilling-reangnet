<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $table = 'packages';
    protected $fillable = [
        'nama',
        'kecepatan',
        'harga',
        'router_id',
        'profile',
    ];

    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class, 'package_id');
    }

    public function router()
    {
        return $this->belongsTo(Router::class, 'router_id');
    }

}
