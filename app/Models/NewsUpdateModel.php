<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsUpdateModel extends Model
{
    use HasFactory;

         //table name
         protected $table = 'm_news';
         //primary key
         protected $primaryKey = 'id';
         //set auto incrementing for PK
         public $incrementing = true;
     
         protected $fillable = [ 'nama','insert_by', 'updated_by','updated_by_ip'];
}
