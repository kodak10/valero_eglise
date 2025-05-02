<?php

namespace App\Models;

use App\Models\Traits\ActivityLogger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Evenement extends Model
{
    use HasFactory, ActivityLogger;

    protected $fillable = [
        'nom',
        'description',
        'qr_code_path',
        'date_evenement',
        'lieu',
        'limite_scan_heure',
        'nombre_scan_max'
    ];

    protected $dates = ['date_evenement'];

    public function getQrCodeUrlAttribute()
    {
        return $this->qr_code_path ? Storage::url($this->qr_code_path) : null;
    }

    public function getDescriptionForEvent(string $eventName): string
     {
         switch ($eventName) {
             case 'created':
                 return "{$this->name} a été créé";
             case 'updated':
                 return "{$this->name} a été mis à jour";
             case 'deleted':
                 return "{$this->name} a été supprimé";
             case 'restored':
                 return "{$this->name} a été restauré";
             case 'forceDeleted':
                 return "{$this->name} a été définitivement supprimé";
             default:
                 return "{$this->name} a effectué une action sur l'utilisateur.";
         }
    }

    /**
     * Les champs modifiables en masse.
     *
     * @var array
     */
   

    /**
     * Les champs à caster en types natifs.
     *
     * @var array
     */
    protected $casts = [
        'date_evenement' => 'datetime',
        'limite_scan_heure' => 'integer',
        'nombre_scan_max' => 'integer',
    ];
}
