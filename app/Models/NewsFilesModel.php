<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsFilesModel extends Model
{
      use HasFactory;
      //table name
      protected $table = 'm_news_file';
      //primary key
      protected $primaryKey = 'id';
      //set auto incrementing for PK
      public $incrementing = true;
  
      protected $fillable = [ 'id_news_dtl','nama','insert_by', 'updated_by','updated_by_ip'];
}
