<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobVacancyFilesModel extends Model
{
    use HasFactory;


    //table name
    protected $table = 'm_job_vacancy_file';
    //primary key
    protected $primaryKey = 'id';
    //set auto incrementing for PK
    public $incrementing = true;

    protected $fillable = [ 'id_job_vacancy_dtl','nama','insert_by', 'updated_by','updated_by_ip'];
}
