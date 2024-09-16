<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KarirModel extends Model
{
    use HasFactory;
    //table name
    protected $table = 'ifg_pages_karir';
    //primary key
    protected $primaryKey = 'id';
    //set auto incrementing for PK
    public $incrementing = true;

    protected $fillable = ['id_content', 'divisi', 'start_date', 'end_date', 'title', 'title_en', 'description', 'description_en', 'work_type', 'url', 'city', 'thumbnail', 'insert_by', 'updated_by','updated_by_ip'];
}
