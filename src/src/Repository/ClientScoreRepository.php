<?php

namespace App\Repository;

use App\Entity\ClientScore;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClientScore>
 *
 * @method ClientScore|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientScore|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientScore[]    findAll()
 * @method ClientScore[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientScoreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientScore::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ClientScore $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(ClientScore $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
