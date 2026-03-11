<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{

protected $fillable = [

'client_id',
'caregiver_id',
'schedule_date',
'start_time',
'end_time'

];

public function client()
{
return $this->belongsTo(Client::class);
}

public function caregiver()
{
return $this->belongsTo(Caregiver::class);
}

}
