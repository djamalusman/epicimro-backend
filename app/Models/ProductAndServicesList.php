<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAndServicesList extends Model
{
    use HasFactory;
    //table name
    protected $table = 'ifg_anggota_produk_dan_layanan';
    //primary key
    protected $primaryKey = 'id';
    //set auto incrementing for PK
    public $incrementing = true;
    
    protected $fillable = ['id_content', 'product_name', 'product_name_en','flag', 'insert_by', 'updated_by','updated_by_ip'];
}
