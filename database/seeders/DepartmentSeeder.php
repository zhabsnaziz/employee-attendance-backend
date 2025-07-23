<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now('Asia/Jakarta');
    
        Department::insert([
            ['department_name' => 'IT', 'max_clock_in_time' => '08:00:00', 'max_clock_out_time' => '17:00:00', 'created_at' => $now, 'updated_at' => $now],
            ['department_name' => 'HR', 'max_clock_in_time' => '09:00:00', 'max_clock_out_time' => '18:00:00', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
