<?php

namespace RemiSan\TransactionManager\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use RemiSan\TransactionManager\Exception\BeginException;
use RemiSan\TransactionManager\Exception\CommitException;
use RemiSan\TransactionManager\Exception\RollbackException;
use RemiSan\TransactionManager\Transactional;

final class DoctrineEntityManager implements Transactional
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * Constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function beginTransaction()
    {
        if (!$this->entityManager->isOpen()) {
            throw new BeginException('Entity Manager is closed');
        }

        try {
            $this->entityManager->beginTransaction();
        } catch (\Exception $e) {
            throw new BeginException('Cannot begin Doctrine ORM transaction', $e->getCode(), $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function commit()
    {
        try {
            $this->entityManager->commit();
        } catch (\Exception $e) {
            throw new CommitException('Cannot commit Doctrine ORM transaction', $e->getCode(), $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rollback()
    {
        try {
            $this->entityManager->rollback();
        } catch (\Exception $e) {
            throw new RollbackException('Cannot rollback Doctrine ORM transaction', $e->getCode(), $e);
        }
    }
}
