<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pre-generate the Admin User's UUID to solve the circular dependency
        $adminUserId = Str::uuid()->toString();

        // 2. Create the Employee Profile First
        $adminEmployee = Employee::create([
            'emp_code' => 'ADMIN001',
            'first_name' => 'System',
            'last_name' => 'Administrator',
            'role' => 'Partner',
            'joining_date' => now(),
            'is_active' => true,
            'created_by_id' => $adminUserId, // The audit trail points to the user we are about to create
        ]);

        // 3. Generate the Cryptographic Hashes (Matching your C# SHA256 + Salt logic)
        $salt = base64_encode(random_bytes(16));
        $password = 'admin123'; // Default setup password
        
        $combinedString = $password . $salt;
        $hashedBytes = hash('sha256', $combinedString, true);
        $passwordHash = base64_encode($hashedBytes);

        // 4. Create the User Account mapped to the Employee
        User::create([
            'id' => $adminUserId,
            'username' => 'admin',
            'password_hash' => $passwordHash,
            'salt' => $salt,
            'is_admin' => true,
            'is_active' => true,
            'requires_password_change' => true, // <-- ADD THIS LINE
            'employee_id' => $adminEmployee->id,
        ]);

        $this->command->info('Database seeded successfully!');
        $this->command->info('Username: admin');
        $this->command->info('Password: admin123');
    }
}