<?php

namespace spec\RemiSan\TransactionManager;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use RemiSan\TransactionManager\Transactional;

class SimpleTransactionManagerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('RemiSan\TransactionManager\SimpleTransactionManager');
    }

    function it_should_be_impossible_to_add_a_new_item_if_transaction_has_started(
        Transactional $item1,
        Transactional $item2
    ) {
        $this->addTransactionalItem($item1);
        $this->beginTransaction();

        $this->shouldThrow('\RemiSan\TransactionManager\Exception\TransactionException')
             ->duringAddTransactionalItem($item2);
    }

    function it_should_begin_a_transaction_on_the_sub_items(Transactional $item)
    {
        $item->beginTransaction()->shouldBeCalled();

        $this->addTransactionalItem($item);
        $this->beginTransaction();
    }

    function it_should_fail_begining_a_transaction_when_there_are_no_sub_items()
    {
        $this->shouldThrow('\RemiSan\TransactionManager\Exception\BeginException')
            ->duringBeginTransaction();
    }

    function it_should_fail_begining_a_transaction_twice(Transactional $item)
    {
        $this->addTransactionalItem($item);
        $this->beginTransaction();
        $this->shouldThrow('\RemiSan\TransactionManager\Exception\BeginException')
            ->duringBeginTransaction();
    }

    function it_should_commit_on_the_sub_items(Transactional $item)
    {
        $item->beginTransaction()->shouldBeCalled();
        $item->commit()->shouldBeCalled();

        $this->addTransactionalItem($item);
        $this->beginTransaction();
        $this->commit();
    }

    function it_should_fail_committing_if_transaction_has_not_been_started()
    {
        $this->shouldThrow('\RemiSan\TransactionManager\Exception\NoRunningTransactionException')
             ->duringCommit();
    }

    function it_should_rollback_on_the_sub_items(Transactional $item)
    {
        $item->beginTransaction()->shouldBeCalled();
        $item->rollback()->shouldBeCalled();

        $this->addTransactionalItem($item);
        $this->beginTransaction();
        $this->rollback();
    }

    function it_should_fail_rollbacking_if_transaction_has_not_been_started()
    {
        $this->shouldThrow('\RemiSan\TransactionManager\Exception\NoRunningTransactionException')
             ->duringRollback();
    }
}
