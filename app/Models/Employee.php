<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employee';
    protected $primaryKey = 'employeeID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    public function classes()
    {
        return $this->belongsToMany(
            \App\Models\Classes::class,
            'teacher_class',
            'employeeID',
            'classID'
        );
    }
}
