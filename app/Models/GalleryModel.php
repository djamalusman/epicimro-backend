<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryModel extends Model
{
    use HasFactory;
      //table name
      protected $table = 'd_gallery';
      //primary key
      protected $primaryKey = 'id';
      //set auto incrementing for PK
      public $incrementing = true;
  
      protected $fillable = [ 'nama','id_menu','id_category','insert_by', 'updated_by','updated_by_ip','status'];
}
