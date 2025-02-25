<?php

namespace App\Models;

use App\Models\Scopes\CoursEnseignantScope;
use App\Services\Admin\Models\Faculte;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Auditable;


class CoursEnseignant extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, HasUlids, Auditable;

    protected $casts = [
        'assistants' => 'array',
        'promotion_codes' => 'array'
    ];
    protected $with = [
        'cours', 'enseignant'
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new CoursEnseignantScope());
    }

    public function getLabelAttribute(): string
    {
        return $this->cours?->nom . " - " . $this->display_promotions;
    }

    public function getDisplayPromotionsAttribute(): ?string
    {
        if (!$this->promotion_codes) return null;

        return implode(', ', $this->promotion_codes);
    }

    public function cours(): BelongsTo
    {
        return $this->belongsTo(Cours::class);
    }

    public function enseignant(): BelongsTo
    {
        return $this->belongsTo(Enseignant::class);
    }

    public function getFaculteByCode(): ?Faculte
    {
        return Faculte::whereCode($this->faculte_code)->first();
    }

    public function assistants(): Collection
    {
        if (!$this->assistants) return new Collection();
        return Enseignant::whereIn('id', $this->assistants)->get();
    }

    public function getProgress(): float|int
    {
        $totalHeures = $this->totalHeures();
        $totalHeuresPr = $this->totalHeuresPr();

        if ($totalHeures === 0) return 0;
        return round($totalHeuresPr / $totalHeures * 100, 2);
    }

    public function totalHeures(): int
    {
        return $this->cours->volumeHoraire();
    }

    public function totalHeuresPr(): int
    {
        $totalHeuresPr = 0;
        foreach ($this->horaires as $horaire) {
            $totalHeuresPr += $horaire->totalHeuresPr();
        }
        return $totalHeuresPr;
    }

    /**
     * @throws Exception
     */
    public function totalHeuresHoraire(): int
    {
        $totalHeuresPr = 0;
        foreach ($this->horaires as $horaire) {
            $totalHeuresPr += $horaire->totalHeures();
        }
        return $totalHeuresPr;
    }

    public function dateDebut(): ?string
    {
        return $this->horaires->min('date');
    }

    public function dateFin(): ?string
    {
        return $this->horaires->max('date');
    }

    public function delete(): ?bool
    {
        // prevent deletion if there are moyennes otherwise delete

        if ($this->moyennes()->count() == 0) {

            $this->horaires()->each(function ($horaire) {
                $horaire->delete();
            });
        }
        return parent::delete();
    }

    public function moyennes(): hasMany
    {
        return $this->hasMany(Moyenne::class)->orderBy('cote', 'desc');
    }

    public function horaires(): hasMany
    {
        return $this->hasMany(Horaire::class);
    }


}
