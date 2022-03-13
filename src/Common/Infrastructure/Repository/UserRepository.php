<?php

namespace App\Common\Infrastructure\Repository;

use App\Common\Domain\Model\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class UserRepository extends ServiceEntityRepository implements UserLoaderInterface
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function loadUserByIdentifier(string $identifier): ?User
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT u, uc
                FROM App\Common\Domain\Model\User u
                LEFT JOIN u.contact as uc
                WHERE u.username = :identifier')
            ->setParameter('identifier', $identifier)
            ->getOneOrNullResult();
    }

    /** @deprecated since Symfony 5.3 */
    public function loadUserByUsername(string $username): ?User
    {
        return $this->loadUserByIdentifier($username);
    }

    public function save(User $user): void {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($user);
        $entityManager->flush();
    }
}