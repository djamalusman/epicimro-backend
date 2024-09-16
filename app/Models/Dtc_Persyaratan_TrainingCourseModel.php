<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dtc_Persyaratan_TrainingCourseModel extends Model
{
    use HasFactory;
     //table name
     protected $table = 'dtc_persyaratan_training_course';

      //primary key
      protected $primaryKey = 'id';
      //set auto incrementing for PK
      public $incrementing = true;
      
     protected $fillable = ['id_training_course_dtl', 'nama','traning_name','training_duration','insert_by', 'updated_by','updated_by_ip']; 
}

