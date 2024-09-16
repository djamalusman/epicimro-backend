<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenIg extends Model
{
    use HasFactory;
    //table name
    protected $table = 'token_ig';
    //primary key
    protected $primaryKey = 'id';
    //set auto incrementing for PK
    public $incrementing = true;

    protected $fillable = ['token', 'created_at', 'updated_at'];
}
