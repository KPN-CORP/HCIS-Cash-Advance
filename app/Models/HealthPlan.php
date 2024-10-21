<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class HealthPlan extends Model
{
    use HasFactory, HasUuids;
    use SoftDeletes;

    protected $table = 'health_plan';
    protected $fillable = [
        'plan_id',
        'employee_id',
        'medical_type',
        'balance',
        'period',
        'created_by',
    ];
}
