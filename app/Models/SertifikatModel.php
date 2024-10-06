<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SertifikatModel extends Model
{
    use HasFactory;
    //table name
    protected $table = 'd_sertifikat';
    //primary key
    protected $primaryKey = 'id';
    //set auto incrementing for PK
    public $incrementing = true;

    protected $fillable = [ 'nama_peserta','email','nama_training','tanggal_training','no_urut_srt','kode_category_training_srt','kode_srt','tahun_training_srt','no_sertifikat','status','insert_by', 'updated_by','updated_by_ip','status'];
}
