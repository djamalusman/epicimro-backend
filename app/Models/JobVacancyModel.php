<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobVacancyModel extends Model
{
    use HasFactory;


     //table name
     protected $table = 'm_job_vacancy';
     //primary key
     protected $primaryKey = 'id';
     //set auto incrementing for PK
     public $incrementing = true;
 
     protected $fillable = [ 'nama','insert_by', 'updated_by','updated_by_ip'];

}
