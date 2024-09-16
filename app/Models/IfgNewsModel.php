<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IfgNewsModel extends Model
{
    use HasFactory;
    protected $table = 'ifg_news';
    protected $primaryKey = 'id';

    protected $fillable = [
        'news_table_id', 'mnc_table_id', 'news_title', 'news_title_english', 'news_highlight', 'news_highlight_english', 'news_highlight', 'news_highlight_english', 'news_urlmovie', 'news_imagepreview', 'news_image', 'news_urlstatus',
        'news_url', 'news_prioritystatus', 'keterangan_prioritas', 'entry_date', 'entry_id', 'entry_name', 'foto_entry', 'kategori'
    ];
}
