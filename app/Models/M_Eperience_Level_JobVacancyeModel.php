<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_Eperience_Level_JobVacancyeModel extends Model
{
    use HasFactory;
    //table name
    protected $table = 'm_experience_level';
    //primary key
    protected $primaryKey = 'id';
    //set auto incrementing for PK
    public $incrementing = true;
}
