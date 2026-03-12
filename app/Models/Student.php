<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'student';
    protected $primaryKey = 'studentID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    public function class()
    {
        return $this->belongsTo(\App\Models\Classes::class, 'classID', 'classID');
    }
    public function subjects()
    {
        return $this->belongsToMany(\App\Models\Subjects::class, 'student_subject', 'studentID', 'subjectID');
    }
    public function parent()
    {
        return $this->belongsTo(\App\Models\ParentModel::class, 'parentID', 'parentID');
    }
    public function studentSubjects()
    {
        return $this->hasMany(\App\Models\StudentSubject::class, 'studentID', 'studentID');
    }
}
