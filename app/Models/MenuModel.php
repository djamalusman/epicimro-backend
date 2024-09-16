<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MenuModel extends Model
{
    use HasFactory;
    //table name
    protected $table = 'ifg_menu';
    //primary key
    protected $primaryKey = 'id';
    //set auto incrementing for PK
    public $incrementing = true;
    //disable timestamps

    public static function getMenuParent()
    {
        return DB::table('ifg_menu')->whereIn('is_parent', array('Y', '-'))->whereNotIn('menu_name', array('Master','Footer'))->where(function ($query) {
            $query->where('parent_id', '0')->orWhereNull('parent_id');
        })->get();
    }

    public static function getMenuChildKip($id)
    {
        return DB::table('ifg_menu')->whereIn('is_parent', array('N', 'Y'))->where('parent_id_kip', $id)->get();
    }

    public static function getMenuChild($id)
    {
        return DB::table('ifg_menu')->whereIn('is_parent', array('N', 'Y'))->where('parent_id', $id)->get();
    }
}

