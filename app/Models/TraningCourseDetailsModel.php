<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraningCourseDetailsModel extends Model
{
    use HasFactory;
     //table name
     protected $table = 'dtc_training_course_detail';


     protected $fillable = ['id_m_category_training_course', 'id_m_jenis_sertifikasi_training_course','company_name','traning_name','training_duration',
     'startdate','enddate','registrationfee',
     'typeonlineoffile','file','status','generatenumber','lokasi','yotube','abouttraining','abouttrainer','aboutcareer',
     'link_pendaftaran','insert_by', 'updated_by','updated_by_ip','id_provinsi'];

}
