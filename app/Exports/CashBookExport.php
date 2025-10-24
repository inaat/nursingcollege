<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CashBookExport implements FromArray, WithHeadings
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $exportData = [];
        
        // Add headers
        $headers = ['Date', 'Particulars', 'V/N', 'Discount'];
        foreach ($this->data['cashAccounts'] as $account) {
            $headers[] = $account->name . ' (Cash) Dr';
            $headers[] = $account->name . ' (Cash) Cr';
        }
        foreach ($this->data['bankAccounts'] as $account) {
            $headers[] = $account->name . ' (Bank) Dr';
            $headers[] = $account->name . ' (Bank) Cr';
        }
        
        // Add opening balance
        // Add transactions
        // Add totals
        // Implement similar logic to your view, but return as array
        
        return $exportData;
    }

    public function headings(): array
    {
        $headings = ['Date', 'Particulars', 'V/N', 'Discount'];
        foreach ($this->data['cashAccounts'] as $account) {
            $headings[] = $account->name . ' (Cash) Dr';
            $headings[] = $account->name . ' (Cash) Cr';
        }
        foreach ($this->data['bankAccounts'] as $account) {
            $headings[] = $account->name . ' (Bank) Dr';
            $headings[] = $account->name . ' (Bank) Cr';
        }
        return $headings;
    }
}