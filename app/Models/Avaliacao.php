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
        'aTipo',
        'nIdEmpresa',
        'nIdAtendente',
        'nIdUsuarioGerador',
        'aNomeCliente',
        'aEmailCliente',
        'nNotaAtendimento',
        'nAtendeuExpectativas',
        'aComentario',
        'dCriadoEm',
        'dAvaliadoEm'
    ];

    protected $casts = [
        'dCriadoEm' => 'datetime',
        'dAvaliadoEm' => 'datetime',
        'nAtendeuExpectativas' => 'integer',
        'nNotaAtendimento' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($avaliacao) {
            if (empty($avaliacao->aToken)) {
                $avaliacao->aToken = Str::random(32);
            }
            if (empty($avaliacao->dCriadoEm)) {
                $avaliacao->dCriadoEm = now();
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

    // Alias para compatibilidade - vendedor é o mesmo que atendente
    public function vendedor(): BelongsTo
    {
        return $this->atendente();
    }

    public function usuarioGerador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'nIdUsuarioGerador', 'ID');
    }

    public function foiAvaliada(): bool
    {
        if ($this->aTipo === 'comercial') {
            return !is_null($this->nNotaAtendimento) && !is_null($this->nAtendeuExpectativas) && !is_null($this->dAvaliadoEm);
        }
        return !is_null($this->nNotaAtendimento) && !is_null($this->dAvaliadoEm);
    }

    public function foiGerada(): bool
    {
        return !is_null($this->dCriadoEm);
    }

    public function getTaxaConversaoAttribute(): float
    {
        return $this->foiAvaliada() ? 100.0 : 0.0;
    }

    public function getTextoNotaAttribute(): string
    {
        return match($this->nNotaAtendimento) {
            1 => $this->aTipo === 'comercial' ? 'Péssimo' : 'Muito Insatisfeito',
            2 => $this->aTipo === 'comercial' ? 'Ruim' : 'Insatisfeito',
            3 => $this->aTipo === 'comercial' ? 'Regular' : 'Neutro',
            4 => $this->aTipo === 'comercial' ? 'Bom' : 'Satisfeito',
            5 => $this->aTipo === 'comercial' ? 'Excelente' : 'Muito Satisfeito',
            default => 'Não avaliado'
        };
    }

    public function getTextoAtendeuExpectativasAttribute(): string
    {
        return match($this->nAtendeuExpectativas) {
            1 => 'Sim',
            2 => 'Parcialmente',
            3 => 'Não',
            default => 'Não avaliado'
        };
    }

    public function getEmojiNotaAttribute(): string
    {
        return match($this->nNotaAtendimento) {
            1 => '⭐',
            2 => '⭐⭐',
            3 => '⭐⭐⭐',
            4 => '⭐⭐⭐⭐',
            5 => '⭐⭐⭐⭐⭐',
            default => ''
        };
    }

    // Scopes para filtrar por tipo
    public function scopeSuporte($query)
    {
        return $query->where('aTipo', 'suporte');
    }

    public function scopeComercial($query)
    {
        return $query->where('aTipo', 'comercial');
    }

    // Métodos para verificar tipo
    public function isSuporte(): bool
    {
        return $this->aTipo === 'suporte';
    }

    public function isComercial(): bool
    {
        return $this->aTipo === 'comercial';
    }
}
