<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Avaliacao extends Model
{
    protected $table = 'avaliacoes';
    protected $primaryKey = 'ID';
    
    protected $fillable = [
        'aToken',
        'nIdEmpresa',
        'nIdAtendente',
        'aNomeCliente',
        'aEmailCliente',
        'nNota',
        'aComentario',
        'dAvaliadoEm'
    ];

    protected $casts = [
        'dAvaliadoEm' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($avaliacao) {
            if (empty($avaliacao->aToken)) {
                $avaliacao->aToken = Str::random(32);
            }
        });
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'nIdEmpresa', 'ID');
    }

    public function atendente(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'nIdAtendente', 'ID');
    }

    public function foiAvaliada(): bool
    {
        return !is_null($this->nNota) && !is_null($this->dAvaliadoEm);
    }

    public function getTextoNotaAttribute(): string
    {
        return match($this->nNota) {
            1 => 'Muito Insatisfeito',
            2 => 'Insatisfeito',
            3 => 'Neutro',
            4 => 'Satisfeito',
            5 => 'Muito Satisfeito',
            default => 'Não avaliado'
        };
    }
}
