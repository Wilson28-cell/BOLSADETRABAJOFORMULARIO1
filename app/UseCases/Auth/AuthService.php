<?php

namespace App\UseCases\Auth;

use App\Domain\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function authenticate(string $correo, string $password): ?object
    {
        $usuario = $this->userRepository->findByEmail($correo);

        if (!$usuario) {
            return null;
        }

        return $this->matchesPassword($password, $usuario->password) ? $usuario : null;
    }

    public function matchesPassword(string $password, ?string $storedPassword): bool
    {
        if (empty($storedPassword)) {
            return false;
        }

        if ($this->isBcryptHash($storedPassword) && Hash::check($password, $storedPassword)) {
            return true;
        }

        return hash_equals($storedPassword, $password);
    }

    private function isBcryptHash(string $value): bool
    {
        return str_starts_with($value, '$2y$')
            || str_starts_with($value, '$2b$')
            || str_starts_with($value, '$2a$')
            || str_starts_with($value, '$2x$');
    }
}
