<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanTahunanModel extends Model
{
    use HasFactory;
    //table name
    protected $table = 'ifg_pages_laporan_tahunan';
    //primary key
    protected $primaryKey = 'id';
    //set auto incrementing for PK
    public $incrementing = true;
    
    protected $fillable = ['id_content', 'date_report', 'file', 'file2','type', 'title', 'title_en', 'insert_by', 'updated_by','updated_by_ip'];
}
