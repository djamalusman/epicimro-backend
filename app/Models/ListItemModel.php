<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListItemModel extends Model
{
    use HasFactory;
    //table name
    protected $table = 'ifg_pages_content';
    //primary key
    protected $primaryKey = 'id';
    //set auto incrementing for PK
    public $incrementing = true;

    protected $fillable = ['id_menu','id_pages_content_order', 'item_extras', 'item_title', 'item_title_en', 'item_body', 'item_body_en', 'item_link', 'item_file', 'item_file_2', 'item_order' ,'insert_by', 'updated_by','updated_by_ip'];
}
