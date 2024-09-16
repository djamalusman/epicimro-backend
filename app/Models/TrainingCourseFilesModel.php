<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingCourseFilesModel extends Model
{
    use HasFactory;
    //table name
    protected $table = 'dtc_training_course_file';
    //primary key
    protected $primaryKey = 'id';
    //set auto incrementing for PK
    public $incrementing = true;

    protected $fillable = ['id_training_course_dtl', 'nama','insert_by', 'updated_by','updated_by_ip'];
}
