<?php

namespace spec\RemiSan\TransactionManager\Amqp;

use Burrow\QueuePublisher;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TransactionalQueuePublisherSpec extends ObjectBehavior
{
    function let(QueuePublisher $publisher)
    {
        $this->beConstructedWith($publisher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('RemiSan\TransactionManager\Amqp\TransactionalQueuePublisher');
    }

    function it_is_not_possible_to_publish_outside_a_transaction()
    {
        $this->shouldThrow('\RemiSan\TransactionManager\Exception\TransactionException')
            ->duringPublish('', '');
    }

    function it_should_publish_message_when_committing(QueuePublisher $publisher)
    {
        $this->beginTransaction();
        $publisher->publish('', '', [])->shouldBeCalled();
        $this->publish('', '');
        $this->commit();
    }

    function it_should_not_publish_message_when_rollbacking(QueuePublisher $publisher)
    {
        $this->beginTransaction();
        $publisher->publish('', '', [])->shouldNotBeCalled();
        $this->publish('', '');
        $this->rollback();
    }
}
