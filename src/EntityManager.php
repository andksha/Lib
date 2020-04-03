<?php

namespace Framework;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class EntityManager implements Contract\EntityManager
{
    private EntityManagerInterface $entityManager;
    private QueryBuilder $queryBuilder;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function persist($object): void
    {
        $this->entityManager->persist($object);
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function select($select = null): Contract\EntityManager
    {
        $this->queryBuilder = $this->entityManager->createQueryBuilder()->select($select);

        return $this;
    }

    public function from($from, $alias, $indexBy = null): Contract\EntityManager
    {
        $this->queryBuilder->from($from, $alias, $indexBy);

        return $this;
    }

    public function orderBy($sort, $order = null): Contract\EntityManager
    {
        $this->queryBuilder->orderBy($sort, $order);

        return $this;
    }

    public function paginate(int $perPage = 3, int $currentPage = 1): array
    {
        $firstResult = $perPage * ($currentPage - 1);

        $query = $this->queryBuilder->setFirstResult($firstResult)
            ->setMaxResults($perPage);

        $paginator = new Paginator($query);
        $total = count($paginator);
        $totalPages = ceil($total / $perPage);

        return [
            'data'          => $paginator->getQuery()->execute(),
            'current_page'  => $currentPage,
            'total_pages'   => $totalPages,
        ];
    }

    public function find(string $class, int $id)
    {
        return $this->entityManager->find($class, $id);
    }

    public function findBy(string $class, array $criteria): array
    {
        return $this->entityManager->getRepository($class)->findBy($criteria);
    }

    public function findOneBy(string $class, array $criteria)
    {
        return $this->entityManager->getRepository($class)->findOneBy($criteria);
    }
}