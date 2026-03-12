<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Syllabus extends Model
{
    protected $table = 'syllabus';
    protected $primaryKey = 'syllabusID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
}
