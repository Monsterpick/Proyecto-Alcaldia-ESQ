<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Beneficiary extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'first_name',
        'second_name',
        'last_name',
        'second_last_name',
        'document_type',
        'cedula',
        'birth_date',
        'email',
        'phone',
        'state',
        'municipality',
        'parroquia_id',
        'circuito_comunal_id',
        'sector',
        'reference_point',
        'address',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    protected $appends = [
        'full_name',
        'full_cedula',
        'age',
    ];

    // Accessors
    public function getFullNameAttribute(): string
    {
        $name = trim("{$this->first_name} {$this->second_name} {$this->last_name} {$this->second_last_name}");
        return preg_replace('/\s+/', ' ', $name); // Eliminar espacios dobles
    }

    public function getFullCedulaAttribute(): string
    {
        return "{$this->document_type}-{$this->cedula}";
    }

    public function getAgeAttribute(): ?int
    {
        if (!$this->birth_date) {
            return null;
        }
        
        return \Carbon\Carbon::parse($this->birth_date)->age;
    }

    // Relaciones
    public function parroquia(): BelongsTo
    {
        return $this->belongsTo(Parroquia::class);
    }

    public function circuitoComunal(): BelongsTo
    {
        return $this->belongsTo(CircuitoComunal::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('second_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('second_last_name', 'like', "%{$search}%")
              ->orWhere('cedula', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    }
}
