<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Operasional', 'code' => 'OPS'],
            ['name' => 'Sales', 'code' => 'SALES'],
            ['name' => 'HRGA', 'code' => 'HRGA'],
            ['name' => 'Accounting', 'code' => 'ACC'],
            ['name' => 'Infrastructure Support', 'code' => 'INF'],
            ['name' => 'Technical Assistant Center', 'code' => 'TAC'],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(['name' => $dept['name']], $dept);
        }
    }
}
