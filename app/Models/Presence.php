<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    use HasFactory;

    protected $fillable = [
        'evenement_id',
        'nom',
        'prenoms',
        'contact',
        'classe_metho',
        'est_invite',
        'structures',
        'nombre_enfants',
        'nombre_invites',
        'cookie_token',
    ];

    protected $casts = [
        'structures' => 'array',
    ];

    public function evenement()
    {
        return $this->belongsTo(Evenement::class);
    }
}
