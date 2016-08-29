<?php

namespace RemiSan\TransactionManager\Command;

use League\Tactician\CommandBus;
use League\Tactician\Exception\InvalidCommandException;
use RemiSan\TransactionManager\Exception\BeginException;
use RemiSan\TransactionManager\Exception\CommitException;
use RemiSan\TransactionManager\Exception\NoRunningTransactionException;
use RemiSan\TransactionManager\Transactional;

class TransactionalCommandBus extends CommandBus implements Transactional
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var array
     */
    private $commandsToCommit;

    /**
     * @var boolean
     */
    private $transactionRunning;

    /**
     * TransactionalCommandBus constructor.
     *
     * @param CommandBus $commandBus
     */
    public function __construct(CommandBus $commandBus)
    {
        parent::__construct([]); // Build it to prevent warnings but never use it

        $this->commandBus = $commandBus;

        $this->reset();
    }

    /**
     * @inheritDoc
     */
    public function handle($command)
    {
        if (!is_object($command)) {
            throw InvalidCommandException::forUnknownValue($command);
        }

        $this->checkTransactionIsRunning();

        $this->commandsToCommit[] = $command;

        return true;
    }

    /**
     * @inheritDoc
     */
    public function beginTransaction()
    {
        if ($this->isTransactionRunning()) {
            throw new BeginException();
        }

        $this->set();
    }

    /**
     * @inheritDoc
     */
    public function commit()
    {
        $this->checkTransactionIsRunning();

        foreach ($this->commandsToCommit as $command) {
            try {
                $this->commandBus->handle($command);
            } catch (\Exception $e) {
                throw new CommitException($e->getMessage(), $e->getCode(), $e);
            }
        }

        $this->reset();
    }

    /**
     * @inheritDoc
     */
    public function rollback()
    {
        $this->checkTransactionIsRunning();

        $this->reset();
    }

    /**
     * @return bool
     */
    private function isTransactionRunning()
    {
        return (boolean) $this->transactionRunning;
    }

    /**
     * @throws NoRunningTransactionException
     */
    private function checkTransactionIsRunning()
    {
        if (! $this->isTransactionRunning()) {
            throw new NoRunningTransactionException();
        }
    }

    private function set()
    {
        $this->commandsToCommit = [];
        $this->transactionRunning = true;
    }

    private function reset()
    {
        $this->commandsToCommit = null;
        $this->transactionRunning = false;
    }
}
