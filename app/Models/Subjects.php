<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subjects extends Model
{
    protected $table = 'subjects';
    protected $primaryKey = 'subjectID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
}
