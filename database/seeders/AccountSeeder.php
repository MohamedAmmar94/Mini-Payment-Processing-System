<?php
namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $assets = Account::create([
            'code' => '1000',
            'name' => 'Assets',
            'type' => 'asset',
        ]);

        Account::create([
            'code'      => '1100',
            'name'      => 'Cash',
            'type'      => 'asset',
            'parent_id' => $assets->id,
        ]);

        Account::create([
            'code'      => '1200',
            'name'      => 'Bank',
            'type'      => 'asset',
            'parent_id' => $assets->id,
        ]);

        Account::create([
            'code'      => '1300',
            'name'      => 'Accounts Receivable',
            'type'      => 'asset',
            'parent_id' => $assets->id,
        ]);

        $revenue = Account::create([
            'code' => '4000',
            'name' => 'Revenue',
            'type' => 'revenue',
        ]);

        Account::create([
            'code'      => '4100',
            'name'      => 'Sales Revenue',
            'type'      => 'revenue',
            'parent_id' => $revenue->id,
        ]);

        $liabilities = Account::create([
            'code' => '2000',
            'name' => 'Liabilities',
            'type' => 'liability',
        ]);

        Account::create([
            'code'      => '2100',
            'name'      => 'Accounts Payable',
            'type'      => 'liability',
            'parent_id' => $liabilities->id,
        ]);

        Account::create([
            'code'      => '2200',
            'name'      => 'Loans Payable',
            'type'      => 'liability',
            'parent_id' => $liabilities->id,
        ]);

        $equity = Account::create([
            'code' => '3000',
            'name' => 'Equity',
            'type' => 'equity',
        ]);

        Account::create([
            'code'      => '3100',
            'name'      => "Owner's Equity",
            'type'      => 'equity',
            'parent_id' => $equity->id,
        ]);

        $expenses = Account::create([
            'code' => '5000',
            'name' => 'Expenses',
            'type' => 'expense',
        ]);

        Account::create([
            'code'      => '5100',
            'name'      => 'Rent expense',
            'type'      => 'expense',
            'parent_id' => $expenses->id,
        ]);

        Account::create([
            'code'      => '5300',
            'name'      => 'Salary  Expenses',
            'type'      => 'expense',
            'parent_id' => $expenses->id,
        ]);
        Account::create([
            'code'      => '5200',
            'name'      => 'Utilities expense',
            'type'      => 'expense',
            'parent_id' => $expenses->id,
        ]);
    }
}
