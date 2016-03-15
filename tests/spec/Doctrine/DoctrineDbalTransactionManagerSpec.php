<?php

namespace spec\RemiSan\TransactionManager\Doctrine;

use Doctrine\DBAL\Driver\Connection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use RemiSan\TransactionManager\Exception\BeginException;

class DoctrineDbalTransactionManagerSpec extends ObjectBehavior
{
    function let(Connection $connection)
    {
        $this->beConstructedWith($connection);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('RemiSan\TransactionManager\Doctrine\DoctrineDbalTransactionManager');
    }

    function it_should_begin_the_dbal_transaction(Connection $connection)
    {
        $connection->beginTransaction()->willReturn(true);

        $this->beginTransaction();
    }

    function it_should_throw_an_exception_if_dbal_transaction_begin_failed(Connection $connection)
    {
        $connection->beginTransaction()->willReturn(false);

        $this->shouldThrow('\RemiSan\TransactionManager\Exception\BeginException')
            ->duringBeginTransaction();
    }

    function it_should_commit_the_dbal_transaction(Connection $connection)
    {
        $connection->commit()->willReturn(true);

        $this->commit();
    }

    function it_should_throw_an_exception_if_dbal_transaction_commit_failed(Connection $connection)
    {
        $connection->commit()->willReturn(false);

        $this->shouldThrow('\RemiSan\TransactionManager\Exception\CommitException')
             ->duringCommit();
    }

    function it_should_rollback_the_dbal_transaction(Connection $connection)
    {
        $connection->rollBack()->willReturn(true);

        $this->rollback();
    }

    function it_should_throw_an_exception_if_dbal_transaction_rollback_failed(Connection $connection)
    {
        $connection->rollBack()->willReturn(false);

        $this->shouldThrow('\RemiSan\TransactionManager\Exception\RollbackException')
             ->duringRollback();
    }
}
