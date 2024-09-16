<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerDetailModel extends Model
{
    use HasFactory;
      //table name
      protected $table = 'd_banner_detail';
      //primary key
      protected $primaryKey = 'id';
      //set auto incrementing for PK
      public $incrementing = true;
  
      protected $fillable = [ 'id_banner','nama','fileold','file','insert_by', 'updated_by','updated_by_ip'];
}
