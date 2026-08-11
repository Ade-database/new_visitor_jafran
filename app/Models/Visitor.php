<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone_number',
        'company',
        'category',
        'photo_path',
    ];

    // Status yang dianggap "masih berjalan" — dipakai untuk mencegah tamu
    // punya lebih dari satu kunjungan aktif, dan untuk mendeteksi tamu yang
    // scan QR lagi saat masih checked_in (diarahkan ke halaman checkout).
    public const ACTIVE_STATUSES = ['pending', 'approved', 'checked_in'];

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    // Kunjungan yang sedang berjalan (belum checked_out), kalau ada
    public function activeVisit(): HasOne
    {
        return $this->hasOne(Visit::class)
            ->whereIn('status', self::ACTIVE_STATUSES)
            ->latestOfMany();
    }

    public function isPriority(): bool
    {
        return $this->category === 'prioritas';
    }

    // Dipakai di alur lookup nomor HP: cari visitor berdasarkan nomor HP
    public function scopeByPhone(Builder $query, string $phoneNumber): Builder
    {
        return $query->where('phone_number', $phoneNumber);
    }
}
