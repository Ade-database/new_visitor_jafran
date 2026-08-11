<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_id',
        'employee_id',
        'admin_id',
        'input_by',
        'purpose',
        'status',
        'rejected_reason',
        'submitted_at',
        'approved_at',
        'checked_in_at',
        'checked_out_at',
        'department_id',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'checked_in_at' => 'datetime',
        'checked_out_at' => 'datetime',
    ];

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    // Admin yang approve/reject kunjungan ini
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // User (satpam) yang menginput kunjungan ini, null kalau self-service
    public function inputBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'input_by');
    }

    public function feedback(): HasOne
    {
        return $this->hasOne(VisitFeedback::class);
    }

    // --- Helper transisi status, biar logic lifecycle nggak tersebar ---

    public function approve(User $admin): void
    {
        $this->update([
            'status' => 'approved',
            'admin_id' => $admin->id,
            'approved_at' => now(),
        ]);
    }

    public function reject(User $admin, string $reason): void
    {
        $this->update([
            'status' => 'rejected',
            'admin_id' => $admin->id,
            'rejected_reason' => $reason,
        ]);
    }

    public function checkIn(): void
    {
        $this->update([
            'status' => 'checked_in',
            'checked_in_at' => now(),
        ]);
    }

    public function checkOut(): void
    {
        $this->update([
            'status' => 'checked_out',
            'checked_out_at' => now(),
        ]);
    }
}
