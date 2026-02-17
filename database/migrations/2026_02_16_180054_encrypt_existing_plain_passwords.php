<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Encrypt all existing plain passwords
        $users = DB::table('users')
            ->whereNotNull('plain_password')
            ->where('plain_password', '!=', '')
            ->get();

        foreach ($users as $user) {
            // Check if already encrypted by attempting to decrypt
            try {
                Crypt::decryptString($user->plain_password);
                // Already encrypted, skip
                continue;
            } catch (\Exception $e) {
                // Not encrypted, encrypt it now
                DB::table('users')
                    ->where('id', $user->id)
                    ->update([
                        'plain_password' => Crypt::encryptString($user->plain_password)
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Decrypt all encrypted passwords back to plain text
        $users = DB::table('users')
            ->whereNotNull('plain_password')
            ->where('plain_password', '!=', '')
            ->get();

        foreach ($users as $user) {
            try {
                $decrypted = Crypt::decryptString($user->plain_password);
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['plain_password' => $decrypted]);
            } catch (\Exception $e) {
                // Already plain text, skip
                continue;
            }
        }
    }
};
