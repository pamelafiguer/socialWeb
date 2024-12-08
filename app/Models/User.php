<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    
    public $timestamps = false;

    
    protected $table = 'Usuario';

    
    protected $primaryKey = 'id_usuario';

    
    public $incrementing = true;

    
    protected $keyType = 'int';

    
    protected $fillable = [
        'nombre',
        'apellidos',
        'fecha_nacimiento',
        'Genero',
        'email',
        'passwordd',
        'foto_perfil'
    ];

    
    protected $hidden = [
        'passwordd',
        'remember_token',
    ];

    
    public function getAuthPassword()
    {
        return $this->passwordd;
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
