<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggans';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'jenis', 'nama', 'tgl_daftar', 'no_hp', 'ktp', 'titik_koordinat', 'alamat', 'status', 'paket', 'profile', 'username_pppoe', 'remote_address', 'local_address', 'router_id', 'metode','package_id'];
    public function router()
    {
        return $this->belongsTo(Router::class, 'router_id');
    }
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}
