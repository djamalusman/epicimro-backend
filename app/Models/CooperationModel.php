<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CooperationModel extends Model
{
    use HasFactory;

    protected $table = 'd_cooperation';
    //primary key
    protected $primaryKey = 'id';
    //set auto incrementing for PK
    public $incrementing = true;

    protected $fillable = [ 'nama','url','file','status','insert_by', 'updated_by','updated_by_ip'];
}
