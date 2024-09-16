<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestimonialsModel extends Model
{
    use HasFactory;
      //table name
      protected $table = 'd_testimonials';
      //primary key
      protected $primaryKey = 'id';
      //set auto incrementing for PK
      public $incrementing = true;
  
      protected $fillable = [ 'nama','id_category','id_menu','description','insert_by', 'updated_by','updated_by_ip','status'];
}

