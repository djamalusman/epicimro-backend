<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KontakModel extends Model
{
    use HasFactory;
    //table name
    protected $table = 'ifg_pages_contact';
    //primary key
    protected $primaryKey = 'id';
    //set auto incrementing for PK
    public $incrementing = true;

    protected $fillable = [
        'id_content',
        'address',
        'address_en',
        'instagram_icon',
        'instagram_link',
        'facebook_icon',
        'facebook_link',
        'youtube_icon',
        'youtube_link',
        'phone_icon',
        'phone_number_one',
        'phone_number_two',
        'email_icon',
        'email_link',
        'website_icon',
        'website_link',
        'insert_by', 'updated_by', 'updated_by_ip'
    ];
}
