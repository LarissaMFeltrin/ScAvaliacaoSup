<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Atendente extends Model
{
    protected $table = 'atendentes';
    protected $primaryKey = 'ID';
    
    protected $fillable = [
        'aNome',
        'aEmail',
        'nStatus'
    ];

    protected $casts = [
        'nStatus' => 'integer',
    ];

    // Atendentes são funcionários da SCORDON, não vinculados a empresas específicas

    public function avaliacoes(): HasMany
    {
        return $this->hasMany(Avaliacao::class, 'nIdAtendente', 'ID');
    }

    public function isAtivo(): bool
    {
        return $this->nStatus === 0;
    }

    public function scopeAtivos($query)
    {
        return $query->where('nStatus', 0);
    }
}
