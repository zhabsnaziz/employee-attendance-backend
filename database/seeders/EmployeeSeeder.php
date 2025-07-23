<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now('Asia/Jakarta');
    
        Employee::insert([
        [
            'employee_id' => 'EMP001',
            'departement_id' => 1,
            'name' => 'Zein Habsin Aziz',
            'address' => 'Bogor',
            'created_at' => $now,
            'updated_at' => $now,
        ],
        [
            'employee_id' => 'EMP002',
            'departement_id' => 2,
            'name' => 'Dian Puspita',
            'address' => 'Tangerang',
            'created_at' => $now,
            'updated_at' => $now,
        ],
    ]);
    }
}
