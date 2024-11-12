<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Define la tabla que este modelo utiliza
    protected $table = 'Usuario';

    // Define la clave primaria personalizada
    protected $primaryKey = 'id_usuario';

    // Especifica si la clave primaria es autoincremental o no
    public $incrementing = true;

    // Define el tipo de la clave primaria
    protected $keyType = 'int';

    // Especifica los atributos que pueden ser asignados en masa
    protected $fillable = [
        'nombre', 'apellidos', 'fecha_nacimiento', 'Genero', 'email', 'passwordd', 'foto_perfil'
    ];

    // Oculta los atributos en el array de serialización
    protected $hidden = [
        'passwordd', 'remember_token',
    ];

    // Establece el nombre del campo de contraseña personalizado
    public function getAuthPassword()
    {
        return $this->passwordd;
    }

    /**
     * Los atributos que deben ser convertidos a tipos específicos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
