<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SyncGuruAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guruList = Guru::all();
        $count = 0;

        foreach ($guruList as $guru) {
            $existing = User::where('id_guru', $guru->id_guru)->first();

            if (!$existing) {
                $username = !empty($guru->niup) ? $guru->niup : 'guru_' . $guru->id_guru;
                $email = !empty($guru->niup) ? $guru->niup . '@tpq.local' : 'guru' . $guru->id_guru . '@tpq.local';

                // Check username uniqueness
                $baseUsername = $username;
                $i = 1;
                while (User::where('name', $username)->exists()) {
                    $username = $baseUsername . '_' . $i++;
                }

                User::create([
                    'name' => $username,
                    'email' => $email,
                    'password' => Hash::make('12345678'),
                    'id_guru' => $guru->id_guru,
                    'is_superadmin' => 0,
                ]);

                $count++;
            }
        }

        echo "Synced $count teacher login accounts successfully.\n";
    }
}
