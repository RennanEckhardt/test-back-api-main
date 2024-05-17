<?php

namespace App\Domain\User;
////Persistence criado para cada class
interface UserPersistenceInterface
{
    public function create(User $user): void;
    public function isCpfAlreadyCreated(User $user): bool;
    public function isEmailAlreadyCreated(User $user): bool;
    public function findAll(User $user): array;
    public function isExistentId(User $user): bool;
    public function editName(User $user): void;
    public function editCpf(User $user): void;
    public function editEmail(User $user): void;
    public function find(User $user): User;
    public function softDelete(User $user): bool;

}
