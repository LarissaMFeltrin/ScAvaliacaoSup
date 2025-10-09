<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empresa extends Model
{
    protected $table = 'empresas';
    protected $primaryKey = 'ID';
    
    protected $fillable = [
        'aNome',
        'aEmail',
        'aTelefone',
        'aEndereco',
        'nStatus'
    ];

    protected $casts = [
        'nStatus' => 'integer',
    ];

    public function avaliacoes(): HasMany
    {
        return $this->hasMany(Avaliacao::class, 'nIdEmpresa', 'ID');
    }

    public function avaliacoesSuporte(): HasMany
    {
        return $this->avaliacoes()->suporte();
    }

    public function avaliacoesComerciais(): HasMany
    {
        return $this->avaliacoes()->comercial();
    }

    public function isAtiva(): bool
    {
        return $this->nStatus === 0;
    }

    public function scopeAtivas($query)
    {
        return $query->where('nStatus', 0);
    }
}