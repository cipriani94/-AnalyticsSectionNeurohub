<?php


namespace App\Models\AbstractModel;
use App\Models\InterfaceModel\EloquentTableInfo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;

abstract class AbstractPivot extends Model implements EloquentTableInfo
{
    use HasFactory;

    /*
   |--------------------------------------------------------------------------
   | GLOBAL VARIABLES
   |--------------------------------------------------------------------------
   */
    protected $guarded = ['id'];
    public $timestamps = false;

    public static function getTableName(): string
    {
        return with(new static)->getTable();
    }
}
