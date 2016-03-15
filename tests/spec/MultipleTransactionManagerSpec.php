<?php

namespace spec\RemiSan\TransactionManager;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use RemiSan\TransactionManager\Transactional;

class MultipleTransactionManagerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('RemiSan\TransactionManager\MultipleTransactionManager');
    }

    function it_should_begin_transaction_only_at_first_call(Transactional $item)
    {
        $item->beginTransaction()->shouldBeCalledTimes(1);

        $this->addTransactionalItem($item);
        $this->beginTransaction();

        $this->beginTransaction();
    }

    function it_should_commit_after_it_has_been_called_the_same_number_as_beginTransaction(Transactional $item)
    {
        $item->beginTransaction()->shouldBeCalledTimes(1);
        $item->commit()->shouldBeCalledTimes(1);

        $this->addTransactionalItem($item);

        $this->beginTransaction();
        $this->beginTransaction();

        $this->commit();
        $this->commit();
    }

    function it_should_not_commit_after_it_has_been_called_less_than_beginTransaction(Transactional $item)
    {
        $item->beginTransaction()->shouldBeCalledTimes(1);
        $item->commit()->shouldNotBeCalled();

        $this->addTransactionalItem($item);

        $this->beginTransaction();
        $this->beginTransaction();

        $this->commit();
    }

    function it_should_fail_committing_if_transaction_has_not_been_started()
    {
        $this->shouldThrow('\RemiSan\TransactionManager\Exception\NoRunningTransactionException')
             ->duringCommit();
    }

    function it_should_rollback_right_away(Transactional $item)
    {
        $item->beginTransaction()->shouldBeCalledTimes(1);
        $item->rollback()->shouldBeCalledTimes(1);

        $this->addTransactionalItem($item);

        $this->beginTransaction();
        $this->beginTransaction();

        $this->rollback();
        $this->rollback();
    }
}
