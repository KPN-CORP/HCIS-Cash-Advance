<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class ca_transaction extends Model
{
    use HasFactory, HasUuids;
    protected $keyType = 'string';
    public $incrementing=false;

    public function businessTrip()
    {
        return $this->belongsTo(BusinessTrip::class, 'user_id');
    }
    protected $fillable = [
        'id','type_ca','no_ca','no_sppd','user_id','unit','contribution_level_code','destination','ca_needs','start_date','end_date','date_required','detail_ca','total_ca','total_real','total_cost','approval_status','approval_sett','approval_extend'
    ];
}
