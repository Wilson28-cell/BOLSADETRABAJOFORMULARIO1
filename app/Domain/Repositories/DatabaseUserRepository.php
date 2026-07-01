<?php

namespace App\Domain\Repositories;

use Illuminate\Support\Facades\DB;

class DatabaseUserRepository implements UserRepositoryInterface
{
    public function findByEmail(string $email): ?object
    {
        return DB::table('usuarios')->where('correo', $email)->first();
    }
}
