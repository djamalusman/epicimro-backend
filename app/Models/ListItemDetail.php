<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListItemDetail extends Model
{
    use HasFactory;
    //table name
    protected $table = 'ifg_pages_content_detail';
    //primary key
    protected $primaryKey = 'id';
    //set auto incrementing for PK
    public $incrementing = true;

    protected $fillable = ['id_content', 'order', 'extras', 'id_side_list', 'title', 'title_en', 'description', 'description_en', 'url', 'description2', 'description2_en', 'flag', 'file', 'file2','insert_by', 'updated_by','updated_by_ip'];
}
