<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentModel extends Model
{
    protected $table = 'parent';
    protected $primaryKey = 'parentID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    public function students()
    {
        return $this->hasMany(\App\Models\Student::class, 'parentID', 'parentID');
    }
}
