<?php

namespace Modules\Process\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class FiscalPeriod extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fiscal_periods';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'period',
        'start_date',
        'end_date',
        'status',
        'notes',
        'closed_at',
        'closed_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'closed_at' => 'datetime',
    ];

    /**
     * Get the user who closed the period
     */
    public function closedByUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'closed_by');
    }

    /**
     * Check if period is open
     */
    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    /**
     * Check if period is closed
     */
    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    /**
     * Open the period
     */
    public function open(): bool
    {
        return $this->update([
            'status' => 'open',
            'closed_at' => null,
            'closed_by' => null,
        ]);
    }

    /**
     * Close the period
     */
    public function close(?int $userId = null): bool
    {
        return $this->update([
            'status' => 'closed',
            'closed_at' => now(),
            'closed_by' => $userId ?? auth()->id(),
        ]);
    }

    /**
     * Find or create a fiscal period for a given date
     *
     * @param string|\Carbon\Carbon $date
     * @return FiscalPeriod|null
     */
    public static function findOrCreateForDate($date): ?self
    {
        $date = Carbon::parse($date);
        $period = $date->format('Y-m');

        $fiscalPeriod = self::where('period', $period)->first();

        if (!$fiscalPeriod) {
            $fiscalPeriod = self::create([
                'period' => $period,
                'start_date' => $date->copy()->startOfMonth(),
                'end_date' => $date->copy()->endOfMonth(),
                'status' => 'open',
            ]);
        }

        return $fiscalPeriod;
    }

    /**
     * Check if a date falls within an open period
     *
     * @param string|\Carbon\Carbon $date
     * @return bool
     */
    public static function isDateInOpenPeriod($date): bool
    {
        $date = Carbon::parse($date);
        $period = $date->format('Y-m');

        $fiscalPeriod = self::where('period', $period)->first();

        if (!$fiscalPeriod) {
            // If period doesn't exist, create it as open
            self::findOrCreateForDate($date);
            return true;
        }

        return $fiscalPeriod->isOpen();
    }

    /**
     * Get all periods for a given year
     *
     * @param string|int $year
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getPeriodsForYear($year): \Illuminate\Database\Eloquent\Collection
    {
        $year = (string) $year;
        return self::where('period', 'like', "{$year}-%")
            ->orderBy('period', 'asc')
            ->get();
    }
}

