<?php

namespace App\Models;
use App\Enums\UserRole;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'name',
        'lastname',
        'dni',
        'email',
        'phone',
        'sexo',
        'rol',
        'estado',
        'password',
        'photo',
        'address',
        'fecha_nacimiento'
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
    protected $casts = [
        'rol' => UserRole::class,
        'estado' => 'boolean',
        'fecha_nacimiento' => 'date'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->rol === UserRole::ADMIN;
    }

    public function isSecretaria(): bool
    {
        return $this->rol === UserRole::SECRETARIA;
    }

    public function isDocente(): bool
    {
        return $this->rol === UserRole::DOCENTE;
    }

    public function isEstudiante(): bool
    {
        return $this->rol === UserRole::ESTUDIANTE;
    }

    public function estudiante()
    {
        return $this->hasOne(Estudiante::class, 'user_id');
    }

    public function secretaria()
    {
        return $this->hasOne(Secretaria::class, 'user_id');
    }

    public function docente()
    {
        return $this->hasOne(Docente::class, 'user_id');
    }
      public function tutor()
    {
        return $this->hasOne(Tutor::class, 'user_id', 'user_id');
    }
}
