<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_Employee_Status_JobVacancyeModel extends Model
{
    use HasFactory;
    //table name
    protected $table = 'm_employee_status';
    //primary key
    protected $primaryKey = 'id';
    //set auto incrementing for PK
    public $incrementing = true;
}
