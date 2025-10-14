<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Setup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TransactionDateValidationPerformanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test user
        $this->user = User::factory()->create();
        
        // Create setup with start entry period
        Setup::create([
            'company_name' => 'Test Company',
            'company_address' => 'Test Address',
            'company_phone' => '123456789',
            'company_email' => 'test@example.com',
            'company_logo' => 'test-logo.png',
            'start_entry_period' => '2025-01-01',
        ]);
    }

    /** @test */
    public function middleware_performance_with_caching()
    {
        $this->actingAs($this->user);
        
        // Clear cache first
        Cache::flush();
        
        // First request - should hit database
        $startTime = microtime(true);
        $response = $this->post('/finance/kas/penerimaan', [
            'customer_id' => 1,
            'account_head_id' => 1,
            'currency_id' => 1,
            'date' => '2024-12-31', // Before start entry period
            'no_transactions' => 'TEST/2024/001/1',
            'description' => 'Test transaction'
        ]);
        $firstRequestTime = microtime(true) - $startTime;
        
        // Second request - should use cache
        $startTime = microtime(true);
        $response = $this->post('/finance/kas/penerimaan', [
            'customer_id' => 1,
            'account_head_id' => 1,
            'currency_id' => 1,
            'date' => '2024-12-31', // Before start entry period
            'no_transactions' => 'TEST/2024/001/2',
            'description' => 'Test transaction 2'
        ]);
        $secondRequestTime = microtime(true) - $startTime;
        
        // Cache should make second request faster
        $this->assertLessThan($firstRequestTime, $secondRequestTime);
        
        // Both should have validation errors
        $response->assertSessionHasErrors(['date']);
    }

    /** @test */
    public function middleware_early_return_performance()
    {
        $this->actingAs($this->user);
        
        // Request without date fields - should return early
        $startTime = microtime(true);
        $response = $this->post('/finance/kas/penerimaan', [
            'customer_id' => 1,
            'account_head_id' => 1,
            'currency_id' => 1,
            'no_transactions' => 'TEST/2024/001/1',
            'description' => 'Test transaction'
        ]);
        $noDateRequestTime = microtime(true) - $startTime;
        
        // Request with date fields - should take longer
        $startTime = microtime(true);
        $response = $this->post('/finance/kas/penerimaan', [
            'customer_id' => 1,
            'account_head_id' => 1,
            'currency_id' => 1,
            'date' => '2024-12-31',
            'no_transactions' => 'TEST/2024/001/2',
            'description' => 'Test transaction 2'
        ]);
        $withDateRequestTime = microtime(true) - $startTime;
        
        // Request without date should be faster (early return)
        $this->assertLessThan($withDateRequestTime, $noDateRequestTime);
    }

    /** @test */
    public function cache_invalidation_works_correctly()
    {
        $this->actingAs($this->user);
        
        // First, verify cache is working
        $startEntryPeriod = Setup::getStartEntryPeriod();
        $this->assertEquals('2025-01-01', $startEntryPeriod->format('Y-m-d'));
        
        // Update setup
        $setup = Setup::first();
        $setup->update(['start_entry_period' => '2025-06-01']);
        
        // Clear cache manually (this should be called automatically in controller)
        Setup::clearStartEntryPeriodCache();
        
        // Get updated period
        $newStartEntryPeriod = Setup::getStartEntryPeriod();
        $this->assertEquals('2025-06-01', $newStartEntryPeriod->format('Y-m-d'));
    }

    /** @test */
    public function database_query_count_optimization()
    {
        $this->actingAs($this->user);
        
        // Clear cache
        Cache::flush();
        
        // Enable query logging
        DB::enableQueryLog();
        
        // Make multiple requests
        for ($i = 0; $i < 5; $i++) {
            $this->post('/finance/kas/penerimaan', [
                'customer_id' => 1,
                'account_head_id' => 1,
                'currency_id' => 1,
                'date' => '2024-12-31',
                'no_transactions' => "TEST/2024/001/{$i}",
                'description' => "Test transaction {$i}"
            ]);
        }
        
        $queries = DB::getQueryLog();
        
        // Should only have one query to setup table (first request)
        // Subsequent requests should use cache
        $setupQueries = collect($queries)->filter(function ($query) {
            return str_contains($query['query'], 'setup');
        });
        
        // Should have minimal setup queries due to caching
        $this->assertLessThanOrEqual(2, $setupQueries->count());
    }
}
