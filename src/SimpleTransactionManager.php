<?php

namespace RemiSan\TransactionManager;

use RemiSan\TransactionManager\Exception\BeginException;
use RemiSan\TransactionManager\Exception\NoRunningTransactionException;
use RemiSan\TransactionManager\Exception\TransactionException;

class SimpleTransactionManager implements TransactionManager
{
    /**
     * @var Transactional[]
     */
    private $items;

    /**
     * @var int
     */
    private $running;

    /**
     * Constructor.
     *
     * @param Transactional[] $items
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
        $this->reset();
    }

    /**
     * {@inheritdoc}
     */
    public function addTransactionalItem(Transactional $item)
    {
        if ($this->running) {
            throw new TransactionException('You cannot add a transactional item during a running transaction');
        }

        $this->items[] = $item;
    }

    /**
     * {@inheritdoc}
     */
    public function beginTransaction()
    {
        if ($this->running) {
            throw new BeginException('Transaction already running');
        }

        if (count($this->items) === 0) {
            throw new BeginException('No transaction to start');
        }

        foreach ($this->items as $item) {
            $item->beginTransaction();
        }

        $this->running = true;
    }

    /**
     * {@inheritdoc}
     */
    public function commit()
    {
        $this->checkTransaction();

        foreach ($this->items as $item) {
            $item->commit();
        }

        $this->reset();
    }

    /**
     * {@inheritdoc}
     */
    public function rollback()
    {
        $this->checkTransaction();

        foreach ($this->items as $item) {
            $item->rollback();
        }

        $this->reset();
    }

    /**
     * Check if there's a transaction running.
     *
     * @throws NoRunningTransactionException
     */
    private function checkTransaction()
    {
        if (!$this->running) {
            throw new NoRunningTransactionException('No transaction running');
        }
    }

    /**
     * Reset the transaction.
     */
    private function reset()
    {
        $this->running = false;
    }
}
