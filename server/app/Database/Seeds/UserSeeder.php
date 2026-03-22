<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'full_name' => 'Admin User',
                'email' => 'admin@bank.com',
                'password_hash' => password_hash('admin123', PASSWORD_BCRYPT),
                'role' => 'ops_admin',
                'is_active' => true,
                'last_login_at' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'full_name' => 'John Doe',
                'email' => 'john@bank.com',
                'password_hash' => password_hash('customer123', PASSWORD_BCRYPT),
                'role' => 'customer',
                'is_active' => true,
                'last_login_at' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'full_name' => 'Jane Doe',
                'email' => 'jane@bank.com',
                'password_hash' => password_hash('customer123', PASSWORD_BCRYPT),
                'role' => 'customer',
                'is_active' => true,
                'last_login_at' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('users')->insertBatch($users);
    }
}
