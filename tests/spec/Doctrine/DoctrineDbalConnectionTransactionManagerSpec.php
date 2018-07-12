<?php

namespace spec\RemiSan\TransactionManager\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ConnectionException;
use PhpSpec\ObjectBehavior;
use RemiSan\TransactionManager\Doctrine\DoctrineDbalConnectionTransactionManager;
use RemiSan\TransactionManager\Exception\BeginException;
use RemiSan\TransactionManager\Exception\CommitException;
use RemiSan\TransactionManager\Exception\RollbackException;

class DoctrineDbalConnectionTransactionManagerSpec extends ObjectBehavior
{
    function let(Connection $connection)
    {
        $this->beConstructedWith($connection);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DoctrineDbalConnectionTransactionManager::class);
    }

    function it_should_begin_the_dbal_transaction(Connection $connection)
    {
        $connection->beginTransaction();

        $this->beginTransaction();
    }

    function it_should_throw_an_exception_if_dbal_transaction_begin_failed(Connection $connection)
    {
        $connection->beginTransaction()->willThrow(new \Exception());

        $this->shouldThrow(BeginException::class)
            ->duringBeginTransaction();
    }

    function it_should_commit_the_dbal_transaction(Connection $connection)
    {
        $connection->commit();

        $this->commit();
    }

    function it_should_throw_an_exception_if_dbal_transaction_commit_failed(Connection $connection)
    {
        $connection->commit()->willThrow(ConnectionException::class);

        $this->shouldThrow(CommitException::class)
            ->duringCommit();
    }

    function it_should_rollback_the_dbal_transaction(Connection $connection)
    {
        $connection->rollBack();

        $this->rollback();
    }

    function it_should_throw_an_exception_if_dbal_transaction_rollback_failed(Connection $connection)
    {
        $connection->rollBack()->willThrow(ConnectionException::class);

        $this->shouldThrow(RollbackException::class)
            ->duringRollback();
    }

}
