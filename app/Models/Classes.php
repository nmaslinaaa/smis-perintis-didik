<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $table = 'classes';
    protected $primaryKey = 'classID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    public function students()
    {
        return $this->hasMany(\App\Models\Student::class, 'classID', 'classID');
    }

    public function subjects()
    {
        return $this->belongsToMany(
            \App\Models\Subjects::class,
            'class_subject',
            'classID',
            'subjectID'
        );
    }

    public function teachers()
    {
        return $this->belongsToMany(
            \App\Models\Employee::class,
            'teacher_class',
            'classID',
            'employeeID'
        );
    }
}
