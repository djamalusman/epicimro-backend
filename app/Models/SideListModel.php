<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SideListModel extends Model
{
    use HasFactory;
    //table name
    protected $table = 'ifg_pages_side_list';
    //primary key
    protected $primaryKey = 'id';
    //set auto incrementing for PK
    public $incrementing = true;

    protected $fillable = ['id_menu','id_pages_content', 'id_pages_content_order','side_list', 'side_list_en', 'order', 'insert_by', 'updated_by','updated_by_ip'];
}
