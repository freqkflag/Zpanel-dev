<?php

namespace Database\Seeders;

use App\Models\PrivateKey;
use Illuminate\Database\Seeder;

class PrivateKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PrivateKey::create([
            'team_id' => 0,
            'name' => 'Testing Host Key',
            'description' => 'This is a test docker container',
            'private_key' => '-----BEGIN OPENSSH PRIVATE KEY-----
TEST_KEY_FOR_DEVELOPMENT_ONLY_NOT_A_REAL_SECRET_PLACEHOLDER
TEST_KEY_FOR_DEVELOPMENT_ONLY_NOT_A_REAL_SECRET_PLACEHOLDER
TEST_KEY_FOR_DEVELOPMENT_ONLY_NOT_A_REAL_SECRET_PLACEHOLDER
TEST_KEY_FOR_DEVELOPMENT_ONLY_NOT_A_REAL_SECRET_PLACEHOLDER
TEST_KEY_FOR_DEVELOPMENT_ONLY_NOT_A_REAL_SECRET_PLACEHOLDER
-----END OPENSSH PRIVATE KEY-----
',
        ]);

        PrivateKey::create([
            'team_id' => 0,
            'name' => 'development-github-app',
            'description' => 'This is the key for using the development GitHub app',
            'private_key' => '-----BEGIN RSA PRIVATE KEY-----
TEST_KEY_FOR_DEVELOPMENT_ONLY_NOT_A_REAL_SECRET_PLACEHOLDER
TEST_KEY_FOR_DEVELOPMENT_ONLY_NOT_A_REAL_SECRET_PLACEHOLDER
TEST_KEY_FOR_DEVELOPMENT_ONLY_NOT_A_REAL_SECRET_PLACEHOLDER
TEST_KEY_FOR_DEVELOPMENT_ONLY_NOT_A_REAL_SECRET_PLACEHOLDER
TEST_KEY_FOR_DEVELOPMENT_ONLY_NOT_A_REAL_SECRET_PLACEHOLDER
-----END RSA PRIVATE KEY-----',
            'is_git_related' => true,
        ]);
    }
}
