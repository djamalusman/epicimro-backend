<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_Jenis_Sertifikasi_TrainingCourseModel extends Model
{
    use HasFactory;
    //table name
    protected $table = 'm_jenis_sertifikasi_training_course';
    //primary key
    protected $primaryKey = 'id';
    //set auto incrementing for PK
    public $incrementing = true;
}
