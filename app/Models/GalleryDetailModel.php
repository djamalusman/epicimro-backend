<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryDetailModel extends Model
{
    use HasFactory;
      //table name
      protected $table = 'd_gallery_detail';
      //primary key
      protected $primaryKey = 'id';
      //set auto incrementing for PK
      public $incrementing = true;
  
      protected $fillable = [ 'id_gallery','nama','fileold','file','insert_by', 'updated_by','updated_by_ip'];
}
