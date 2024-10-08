<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RencanaTindakLanjut extends Model
{
    use HasFactory;

    protected $table = 'ss_rencana_tindak_lanjut';

    protected $guarded = ['id'];

    public function r_status()
    {
        return $this->hasOne(Status::class, 'status', 'satusehat_send');
    }
    public function r_encounter()
    {
        return $this->hasOne(Encounter::class,  'original_code', 'encounter_original_code');
    }
}
