<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IfgGaleryModel extends Model
{
    use HasFactory;
    protected $table = 'ifg_gallery';
    protected $primaryKey = 'id';

    protected $fillable = [
        'hgallery_table_id', 'hgallery_title', 'hgallery_title_english', 'hgallery_highlight', 'hgallery_highlight_english', 'hgallery_text', 'hgallery_text_english', 'hgallery_type', 'keterangan_hgallery_type', 'hgallery_imagepreview', 'hgallery_urlsource', 'hgallery_prioritystatus',
        'keterangan_prioritas', 'entry_date', 'entry_id', 'dgallery_table_id', 'dgallery_table_id', 'dgallery_imagepreview', 'dgallery_image', 'dgallery_description', 'urutan', 'foto_entry'
    ];
}
