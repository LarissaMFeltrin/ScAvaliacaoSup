<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'ID';

    protected $fillable = [
        'aNome',
        'aEmail',
        'aSenha',
        'aRole',
        'nIdEmpresa',
        'nStatus'
    ];

    protected $hidden = [
        'aSenha',
        'remember_token',
    ];

    protected $casts = [
        'dEmailVerificadoEm' => 'datetime',
        'nStatus' => 'integer',
    ];

    // Override dos métodos de autenticação
    public function getAuthPassword()
    {
        return $this->aSenha;
    }

    public function getEmailForPasswordReset()
    {
        return $this->aEmail;
    }

    // Relacionamentos
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'nIdEmpresa', 'ID');
    }

    // Scopes
    public function scopeAtivos($query)
    {
        return $query->where('nStatus', 0);
    }

    public function scopeRole($query, $role)
    {
        return $query->where('aRole', $role);
    }

    // Métodos auxiliares
    public function isAtivo(): bool
    {
        return $this->nStatus === 0;
    }

    public function isAdmin(): bool
    {
        return $this->aRole === 'admin';
    }

    public function isSuporte(): bool
    {
        return $this->aRole === 'suporte';
    }

    public function isAtendente(): bool
    {
        return $this->aRole === 'atendente';
    }

    public function isVendedor(): bool
    {
        return $this->aRole === 'vendedor';
    }

    public function hasRole(string $role): bool
    {
        return $this->aRole === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->aRole, $roles);
    }

    // Mutator para senha
    public function setASenhaAttribute($value)
    {
        $this->attributes['aSenha'] = Hash::make($value);
    }

    public function getNomeRoleAttribute(): string
    {
        return match($this->aRole) {
            'admin' => 'Administrador',
            'suporte' => 'Suporte',
            'atendente' => 'Atendente',
            'vendedor' => 'Vendedor',
            default => 'Usuário'
        };
    }
}