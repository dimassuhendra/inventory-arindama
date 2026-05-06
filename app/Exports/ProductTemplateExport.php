<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\ProductInputSheet;
use App\Exports\Sheets\ReferenceSheet;

class ProductTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new ProductInputSheet(), // Sheet untuk input form
            new ReferenceSheet(), // Sheet untuk list/kamus data
        ];
    }
}
