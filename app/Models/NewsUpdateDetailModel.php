<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsUpdateDetailModel extends Model
{
    use HasFactory;
    //table name
    protected $table = 'news_detail';

    protected $fillable = ['id_m_news', 'title','title_en','penulis','implementation_date','description','description_en','insert_by', 'updated_by','updated_by_ip','file','status'];
}
