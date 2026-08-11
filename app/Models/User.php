<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Ini bagian barunya: cuma role admin & satpam yang boleh masuk dashboard
    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->role, ['admin', 'satpam']);
    }

    public function reviewedVisits(): HasMany
    {
        return $this->hasMany(Visit::class, 'admin_id');
    }

    public function inputtedVisits(): HasMany
    {
        return $this->hasMany(Visit::class, 'input_by');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSatpam(): bool
    {
        return $this->role === 'satpam';
    }
}