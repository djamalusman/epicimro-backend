<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryAnggotaHolding extends Model
{
    use HasFactory;
    //table name
    protected $table = 'ifg_anggota_gallery';
    //primary key
    protected $primaryKey = 'id';
    //set auto incrementing for PK
    public $incrementing = true;
    
    protected $fillable = ['id_content', 'picture','insert_by', 'updated_by','updated_by_ip'];
}
