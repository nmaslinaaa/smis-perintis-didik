<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Performance extends Model
{
    protected $table = 'performance';
    protected $primaryKey = 'performanceID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'studentID',
        'teacherID',
        'subjectID',
        'classID',
        'performance_month',
        'test_score',
        'performance_status',
        'teacher_comment',
        'classsubjectID',
    ];
}
