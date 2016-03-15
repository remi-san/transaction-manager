<?php

namespace spec\RemiSan\TransactionManager\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DoctrineEntityManagerSpec extends ObjectBehavior
{
    function let(EntityManagerInterface $entityManager)
    {
        $this->beConstructedWith($entityManager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('RemiSan\TransactionManager\Doctrine\DoctrineEntityManager');
    }

    function it_should_begin_the_em_transaction(EntityManagerInterface $entityManager)
    {
        $entityManager->isOpen()->willReturn(true);
        $entityManager->beginTransaction()->shouldBeCalledTimes(1);

        $this->beginTransaction();
    }

    function it_should_throw_an_exception_if_em_transaction_begin_failed(EntityManagerInterface $entityManager)
    {
        $entityManager->isOpen()->willReturn(true);
        $entityManager->beginTransaction()->willThrow('\Exception');

        $this->shouldThrow('\RemiSan\TransactionManager\Exception\BeginException')
             ->duringBeginTransaction();
    }

    function it_should_throw_an_exception_if_em_is_closed(EntityManagerInterface $entityManager)
    {
        $entityManager->isOpen()->willReturn(false);
        $entityManager->beginTransaction()->shouldNotBeCalled();

        $this->shouldThrow('\RemiSan\TransactionManager\Exception\BeginException')
             ->duringBeginTransaction();
    }

    function it_should_commit_the_em_transaction(EntityManagerInterface $entityManager)
    {
        $entityManager->commit()->shouldBeCalledTimes(1);

        $this->commit();
    }

    function it_should_throw_an_exception_if_em_transaction_commit_failed(EntityManagerInterface $entityManager)
    {
        $entityManager->commit()->willThrow('\Exception');

        $this->shouldThrow('\RemiSan\TransactionManager\Exception\CommitException')
             ->duringCommit();
    }

    function it_should_rollback_the_em_transaction(EntityManagerInterface $entityManager)
    {
        $entityManager->rollback()->shouldBeCalledTimes(1);

        $this->rollback();
    }

    function it_should_throw_an_exception_if_em_transaction_rollback_failed(EntityManagerInterface $entityManager)
    {
        $entityManager->rollback()->willThrow('\Exception');

        $this->shouldThrow('\RemiSan\TransactionManager\Exception\RollbackException')
             ->duringRollback();
    }
}
