<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostIg extends Model
{
    use HasFactory;
    //table name
    protected $table = 'post_ig';
    //primary key
    protected $primaryKey = 'id';
    //set auto incrementing for PK
    public $incrementing = true;

    protected $fillable = ['caption', 'media_type', 'media_url', 'username', 'timestamp', 'created_at', 'updated_at'];
}
