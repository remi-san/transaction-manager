<?php

namespace RemiSan\TransactionManager\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ConnectionException;
use RemiSan\TransactionManager\Exception\BeginException;
use RemiSan\TransactionManager\Exception\CommitException;
use RemiSan\TransactionManager\Exception\RollbackException;
use RemiSan\TransactionManager\Transactional;

final class DoctrineDbalConnectionTransactionManager implements Transactional
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
        try {
            $this->dbalConnection->beginTransaction();
        } catch (\Exception $e) {
            throw new BeginException('Cannot begin Doctrine DBAL transaction', 0, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function commit()
    {
        try {
            $this->dbalConnection->commit();
        } catch (ConnectionException $e) {
            throw new CommitException('Cannot commit Doctrine DBAL transaction', 0, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rollback()
    {
        try {
            $this->dbalConnection->rollBack();
        } catch (ConnectionException $e) {
            throw new RollbackException('Cannot rollback Doctrine DBAL transaction', 0, $e);
        }
    }
}
