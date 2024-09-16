<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestimonialsDetailModel extends Model
{
    use HasFactory;
      //table name
      protected $table = 'd_testimonials_video';
      //primary key
      protected $primaryKey = 'id';
      //set auto incrementing for PK
      public $incrementing = true;
  
      protected $fillable = [ 'id_testimoni','url','insert_by', 'updated_by','updated_by_ip','status'];
}
