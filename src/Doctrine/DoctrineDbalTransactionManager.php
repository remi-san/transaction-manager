<?php

namespace RemiSan\TransactionManager\Doctrine;

use Doctrine\DBAL\Driver\Connection;
use RemiSan\TransactionManager\Exception\BeginException;
use RemiSan\TransactionManager\Exception\CommitException;
use RemiSan\TransactionManager\Exception\RollbackException;
use RemiSan\TransactionManager\Transactional;

final class DoctrineDbalTransactionManager implements Transactional
{
    /**
     * @var Connection
     */
    private $dbalConnection;

    /**
     * Constructor.
     *
     * @param Connection $dbalConnection
     */
    public function __construct(Connection $dbalConnection)
    {
        $this->dbalConnection = $dbalConnection;
    }

    /**
     * {@inheritdoc}
     */
    public function beginTransaction()
    {
        if (!$this->dbalConnection->beginTransaction()) {
            throw new BeginException('Cannot begin Doctrine DBAL transaction');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function commit()
    {
        if (!$this->dbalConnection->commit()) {
            throw new CommitException('Cannot commit Doctrine DBAL transaction');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rollback()
    {
        if (!$this->dbalConnection->rollBack()) {
            throw new RollbackException('Cannot rollback Doctrine DBAL transaction');
        }
    }
}
