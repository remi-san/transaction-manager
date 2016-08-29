<?php

namespace spec\RemiSan\TransactionManager\Command;

use League\Tactician\CommandBus;
use League\Tactician\Exception\InvalidCommandException;
use PhpSpec\Exception\Exception;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use RemiSan\TransactionManager\Exception\BeginException;
use RemiSan\TransactionManager\Exception\CommitException;
use RemiSan\TransactionManager\Exception\NoRunningTransactionException;

class TransactionalCommandBusSpec extends ObjectBehavior
{
    function let(CommandBus $commandBus)
    {
        $this->beConstructedWith($commandBus);
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('RemiSan\TransactionManager\Command\TransactionalCommandBus');
    }

    function it_should_handle_command_when_committing_if_transaction_is_running(
        CommandBus $commandBus,
        \stdClass $command
    ) {
        $this->beginTransaction();
        $this->handle($command);

        $commandBus->handle($command)->shouldBeCalled();

        $this->commit();
    }

    function it_should_not_handle_command_when_rollbacking(CommandBus $commandBus, \stdClass $command)
    {
        $this->beginTransaction();
        $this->handle($command);

        $commandBus->handle($command)->shouldNotBeCalled();

        $this->rollback();
    }

    function it_should_throw_an_exception_if_command_is_invalid()
    {
        $this->beginTransaction();
        $this->shouldThrow(InvalidCommandException::class)
            ->duringHandle('');
    }

    function it_should_throw_an_exception_committing_outside_a_transaction(\stdClass $command)
    {
        $this->shouldThrow(NoRunningTransactionException::class)
            ->duringHandle($command);
    }

    function it_should_throw_an_exception_if_sub_handle_fails(CommandBus $commandBus, \stdClass $command)
    {
        $this->beginTransaction();

        $this->handle($command);

        $commandBus->handle($command)->willThrow(new Exception());

        $this->shouldThrow(CommitException::class)
            ->duringCommit();
    }

    function it_should_throw_an_exception_if_committing_outside_a_transaction() {
        $this->shouldThrow(NoRunningTransactionException::class)
            ->duringCommit();
    }

    function it_should_throw_an_exception_if_rollbacking_outside_a_transaction() {
        $this->shouldThrow(NoRunningTransactionException::class)
            ->duringRollback();
    }

    function it_should_not_be_possible_to_start_a_transaction_more_than_once()
    {
        $this->beginTransaction();
        $this->shouldThrow(BeginException::class)
            ->duringBeginTransaction();
    }
}
