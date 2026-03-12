<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendance';
    protected $primaryKey = 'attendanceID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
}
