<?php

namespace App\Infra\Memory;
use App\Domain\User\UserDataValidator;
use App\Domain\User\User;
use App\Domain\User\UserPersistenceInterface;
use App\Domain\Cpf\CpfGenerator;
use Faker;

class UserMemory implements UserPersistenceInterface
{
    private Faker\Generator $faker;

    public function find(User $user): User{
        $this->faker = Faker\Factory::create();
        $cpf = new CpfGenerator();
        $user
            ->setDataValidator(new UserDataValidator())
            ->setId($this->faker->uuid())
            ->setName($this->faker->name())
            ->setEmail($this->faker->email())
            ->setCpf($cpf->generate())
        
        ;
        return $user;
    }

    public function softDelete(User $user): bool{
        $user->setDeletedAt(date('Y-m-d H:i:s'));
        return false;
    }
    
    public function create(User $user): void
    {
        
    }

    public function isCpfAlreadyCreated(User $user): bool
    {
        return false;
    }

    public function isEmailAlreadyCreated(User $user): bool
    {
        return false;
    }

    public function findAll(User $user): array
    {
        return [];
    }

    public function isExistentId(User $user): bool
    {
        return true;
    }

    public function editName(User $user): void
    {

    }

    public function editCpf(User $user): void
    {

    }

    public function editEmail(User $user): void
    {

    }

}
