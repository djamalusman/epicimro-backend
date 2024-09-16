<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_Fee_JobVacancyeModel extends Model
{
    use HasFactory;
    //table name
    protected $table = 'm_salary_date_month';
    //primary key
    protected $primaryKey = 'id';
    //set auto incrementing for PK
    public $incrementing = true;
}
