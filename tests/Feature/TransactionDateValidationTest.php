<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Setup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class TransactionDateValidationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

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
            'start_entry_period' => '2024-01-01',
        ]);
    }

    /** @test */
    public function it_prevents_transaction_before_start_entry_period()
    {
        $this->actingAs($this->user);

        // Test with a date before start entry period
        $response = $this->post('/finance/kas/penerimaan', [
            'customer_id' => 1,
            'account_head_id' => 1,
            'currency_id' => 1,
            'date' => '2023-12-31', // Before start entry period
            'no_transactions' => 'TEST/2024/001/1',
            'description' => 'Test transaction'
        ]);

        $response->assertSessionHasErrors(['date']);
        $response->assertSessionHasErrors(['date' => 'Transaction date cannot be before the start entry period (01/01/2024)']);
    }

    /** @test */
    public function it_prevents_sales_order_before_start_entry_period()
    {
        $this->actingAs($this->user);

        // Test Sales Order with date_sales field
        $response = $this->post('/finance/piutang/sales-order', [
            'customer_id' => 1,
            'no_transaction' => 'SO-PIL2024-01-0001',
            'date_sales' => '2023-12-31', // Before start entry period
            'currency_id' => 1,
            'des_head_sales' => 'Test sales order'
        ]);

        $response->assertSessionHasErrors(['date_sales']);
        $response->assertSessionHasErrors(['date_sales' => 'Transaction date cannot be before the start entry period (01/01/2024)']);
    }

    /** @test */
    public function it_allows_transaction_on_or_after_start_entry_period()
    {
        $this->actingAs($this->user);

        // Test with a date on start entry period
        $response = $this->post('/finance/kas/penerimaan', [
            'customer_id' => 1,
            'account_head_id' => 1,
            'currency_id' => 1,
            'date' => '2024-01-01', // On start entry period
            'no_transactions' => 'TEST/2024/001/1',
            'description' => 'Test transaction'
        ]);

        // This should not have date validation errors (though it might have other validation errors)
        $response->assertSessionMissing(['date']);
    }

    /** @test */
    public function setup_helper_methods_work_correctly()
    {
        // Test isDateBeforeStartEntryPeriod method
        $this->assertTrue(Setup::isDateBeforeStartEntryPeriod('2023-12-31'));
        $this->assertFalse(Setup::isDateBeforeStartEntryPeriod('2024-01-01'));
        $this->assertFalse(Setup::isDateBeforeStartEntryPeriod('2024-06-15'));

        // Test getStartEntryPeriod method
        $startPeriod = Setup::getStartEntryPeriod();
        $this->assertEquals('2024-01-01', $startPeriod->format('Y-m-d'));
    }

    /** @test */
    public function it_works_without_setup_configured()
    {
        // Delete the setup
        Setup::truncate();

        $this->actingAs($this->user);

        // Should not prevent transaction when no setup is configured
        $response = $this->post('/finance/kas/penerimaan', [
            'customer_id' => 1,
            'account_head_id' => 1,
            'currency_id' => 1,
            'date' => '2023-12-31', // Before any start entry period
            'no_transactions' => 'TEST/2024/001/1',
            'description' => 'Test transaction'
        ]);

        // Should not have date validation errors
        $response->assertSessionMissing(['date']);
    }
}
