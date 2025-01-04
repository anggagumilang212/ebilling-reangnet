<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateNotifikasi extends Model
{
    use HasFactory;

    protected $table = 'template_notifikasis';

    protected $fillable = ['kategori', 'template_pesan'];
}
