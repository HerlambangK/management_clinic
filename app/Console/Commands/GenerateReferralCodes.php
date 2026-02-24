<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Illuminate\Console\Command;

class GenerateReferralCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'referral:generate-codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate referral codes for customers who do not have one';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $customers = Customer::whereNull('referral_code')
            ->orWhere('referral_code', '')
            ->get();

        if ($customers->isEmpty()) {
            $this->info('All customers already have referral codes.');

            return self::SUCCESS;
        }

        $this->info("Found {$customers->count()} customers without referral codes.");

        $bar = $this->output->createProgressBar($customers->count());
        $bar->start();

        foreach ($customers as $customer) {
            $customer->referral_code = Customer::generateReferralCode();
            $customer->save();
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Successfully generated referral codes for {$customers->count()} customers.");

        return self::SUCCESS;
    }
}
