<?php


namespace App\Models\InterfaceModel;


interface EloquentTableInfo
{
    static function getTableName(): string;
}
