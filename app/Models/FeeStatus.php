<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeStatus extends Model
{
    protected $table = 'fee_status';
    protected $primaryKey = 'feestatusID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['studentID', 'feestatus_month', 'status'];
}
