<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherClass extends Model
{
    protected $table = 'teacher_class';
    protected $primaryKey = 'teacherclassID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['classID', 'employeeID'];
}
