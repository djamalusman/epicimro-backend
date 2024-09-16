<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaHoldingModel extends Model
{
    use HasFactory;
    //table name
    protected $table = 'ifg_anggota_holding';
    //primary key
    protected $primaryKey = 'id';
    //set auto incrementing for PK
    public $incrementing = true;

    protected $fillable = ['nama_holding','jenis_holding_short', 'jenis_holding_long', 'order', 'gambar_holding','url', 'insert_by', 'updated_by','updated_by_ip'];
}
