<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSubject extends Model
{
    protected $table = 'class_subject';
    protected $primaryKey = 'classsubjectID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
}
