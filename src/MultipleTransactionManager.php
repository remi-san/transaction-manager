<?php

namespace RemiSan\TransactionManager;

use RemiSan\TransactionManager\Exception\NoRunningTransactionException;

class MultipleTransactionManager extends SimpleTransactionManager
{
    /**
     * @var int number of transactions currently running
     */
    private $transactionCpt = 0;

    /**
     * {@inheritdoc}
     */
    public function beginTransaction()
    {
        $this->transactionCpt++;

        if ($this->transactionCpt === 1) {
            parent::beginTransaction();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function commit()
    {
        $this->transactionCpt--;

        if ($this->transactionCpt < 0) {
            throw new NoRunningTransactionException('Cannot commit before a transaction has begun');
        } elseif ($this->transactionCpt === 0) {
            parent::commit();
            $this->reset();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rollback()
    {
        if ($this->transactionCpt === 0) {
            return;
        }

        parent::rollback();
        $this->reset();
    }

    /**
     * Reset the transaction.
     */
    private function reset()
    {
        $this->transactionCpt = 0;
    }
}
