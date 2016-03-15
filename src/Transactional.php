<?php

namespace RemiSan\TransactionManager;

use RemiSan\TransactionManager\Exception\BeginException;
use RemiSan\TransactionManager\Exception\CommitException;
use RemiSan\TransactionManager\Exception\NoRunningTransactionException;
use RemiSan\TransactionManager\Exception\RollbackException;

interface Transactional
{
    /**
     * Open transaction.
     *
     * @throws BeginException
     */
    public function beginTransaction();

    /**
     * Commit transaction.
     *
     * @throws CommitException
     * @throws NoRunningTransactionException
     */
    public function commit();

    /**
     * Rollback transaction.
     *
     * @throws RollbackException
     * @throws NoRunningTransactionException
     */
    public function rollback();
}
