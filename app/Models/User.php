<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Traits\ActivityLogger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use ActivityLogger, HasFactory, Notifiable;

     // Personnalisation de la description des événements (optionnel, si vous souhaitez une logique plus spécifique)
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
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
