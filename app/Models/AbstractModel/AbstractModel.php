<?php


namespace App\Models\AbstractModel;


use Illuminate\Database\Eloquent\Model;

abstract class AbstractModel extends Model
{
    /*
   |--------------------------------------------------------------------------
   | GLOBAL VARIABLES
   |--------------------------------------------------------------------------
   */
    protected $guarded = ['id'];

    public static function getTableName(): string
    {
        return with(new static)->getTable();
    }
}
