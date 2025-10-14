<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setup extends Model
{
    use HasFactory;

    protected $table = 'setup';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_name',
        'company_address',
        'company_phone',
        'company_email',
        'company_logo',
        'start_entry_period',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_entry_period' => 'date',
    ];

    /**
     * Check if a given date is before the start entry period
     *
     * @param string|\Carbon\Carbon $date
     * @return bool
     */
    public static function isDateBeforeStartEntryPeriod($date): bool
    {
        $startEntryPeriod = self::getStartEntryPeriod();
        if (!$startEntryPeriod) {
            return false;
        }

        $transactionDate = \Carbon\Carbon::parse($date);
        return $transactionDate->lt($startEntryPeriod);
    }

    /**
     * Get the start entry period date with caching
     *
     * @return \Carbon\Carbon|null
     */
    public static function getStartEntryPeriod(): ?\Carbon\Carbon
    {
        return \Illuminate\Support\Facades\Cache::remember('setup_start_entry_period', 3600, function () {
            $setup = self::first();
            return $setup && $setup->start_entry_period ? \Carbon\Carbon::parse($setup->start_entry_period) : null;
        });
    }

    /**
     * Clear the start entry period cache
     * Call this when setup is updated
     */
    public static function clearStartEntryPeriodCache(): void
    {
        \Illuminate\Support\Facades\Cache::forget('setup_start_entry_period');
    }
}
