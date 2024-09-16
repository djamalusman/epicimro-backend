<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobVacancyDetailModel extends Model
{
    use HasFactory;
    //table name
    protected $table = 'djv_job_vacancy_detail';

    protected $primaryKey = 'id';
    //set auto incrementing for PK
    public $incrementing = true;

    protected $fillable = [
        'id_m_employee_status',
        'id_m_work_location',
        'id_m_salaray_date_mont',
        'id_m_salaray',
        'id_m_sector',
        'id_m_education',
        'id_m_experience_level',
        'id_provinsi',
        'lokasi',
        'companyName',
        'file',
        'linkpendaftaran',
        'job_title',
        'sertifikasi',
        'generatenumber',
        'job_description',
        'skill_requirment',
        'posted_date',
        'close_date',
        'insert_by',
        'updated_by',
        'updated_by_ip',
        'status'
    ];
}
