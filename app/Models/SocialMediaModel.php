<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMediaModel extends Model
{
    use HasFactory;
      //table name
      protected $table = 'd_socialmedia';
      //primary key
      protected $primaryKey = 'id';
      //set auto incrementing for PK
      public $incrementing = true;
  
      protected $fillable = [ 'id_category','file','url','insert_by', 'updated_by','updated_by_ip','status'];
}
