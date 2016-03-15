<?php

namespace spec\RemiSan\TransactionManager\Event;

use League\Event\EmitterInterface;
use League\Event\Event;
use League\Event\EventInterface;
use League\Event\GeneratorInterface;
use League\Event\ListenerInterface;
use League\Event\ListenerProviderInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TransactionalEmitterSpec extends ObjectBehavior
{
    function let(EmitterInterface $emitter)
    {
        $this->beConstructedWith($emitter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('RemiSan\TransactionManager\Event\TransactionalEmitter');
    }

    function it_should_emit_event_when_committing_if_transaction_is_running(EmitterInterface $emitter, EventInterface $event)
    {
        $this->beginTransaction();
        $this->emit($event);

        $emitter->emit($event)->shouldBeCalled();

        $this->commit();
    }

    function it_should_emit_string_event_when_committing_if_transaction_is_running(EmitterInterface $emitter)
    {
        $this->beginTransaction();
        $this->emit('test');

        $emitter->emit(Event::named('test'))->shouldBeCalled();

        $this->commit();
    }

    function it_should_emit_events_when_committing_if_transaction_is_running(EmitterInterface $emitter, EventInterface $event)
    {
        $this->beginTransaction();
        $this->emitBatch([$event]);

        $emitter->emit($event)->shouldBeCalled();

        $this->commit();
    }

    function it_should_emit_generated_events_when_committing_if_transaction_is_running(EmitterInterface $emitter, GeneratorInterface $generator, EventInterface $event)
    {
        $generator->releaseEvents()->willReturn([$event]);

        $this->beginTransaction();
        $this->emitGeneratedEvents($generator);

        $emitter->emit($event)->shouldBeCalled();

        $this->commit();
    }

    function it_should_not_emit_event_when_rollbacking(EmitterInterface $emitter, EventInterface $event)
    {
        $this->beginTransaction();
        $this->emit($event);

        $emitter->emit($event)->shouldNotBeCalled();

        $this->rollback();
    }

    function it_should_throw_an_exception_if_event_is_invalid(EmitterInterface $emitter)
    {
        $this->beginTransaction();
        $this->shouldThrow('\InvalidArgumentException')
             ->duringEmit(new \stdClass());
    }

    function it_should_throw_an_exception_if_transaction_is_not_running(EmitterInterface $emitter, EventInterface $event)
    {
        $this->shouldThrow('\RemiSan\TransactionManager\Exception\TransactionException')
            ->duringEmit($event);
    }

    function it_should_proxy_removeListener(EmitterInterface $emitter, ListenerInterface $listener)
    {
        $emitter->removeListener('test', $listener)->shouldBeCalled();

        $this->removeListener('test', $listener);
    }

    function it_should_proxy_useListenerProvider(EmitterInterface $emitter, ListenerProviderInterface $listenerProvider)
    {
        $emitter->useListenerProvider($listenerProvider)->shouldBeCalled();

        $this->useListenerProvider($listenerProvider);
    }

    function it_should_proxy_removeAllListeners(EmitterInterface $emitter)
    {
        $emitter->removeAllListeners('test')->shouldBeCalled();

        $this->removeAllListeners('test');
    }

    function it_should_proxy_hasListeners(EmitterInterface $emitter)
    {
        $emitter->hasListeners('test')->shouldBeCalled();

        $this->hasListeners('test');
    }

    function it_should_proxy_getListeners(EmitterInterface $emitter)
    {
        $emitter->getListeners('test')->shouldBeCalled();

        $this->getListeners('test');
    }

    function it_should_proxy_addListener(EmitterInterface $emitter, ListenerInterface $listener)
    {
        $emitter->addListener('test', $listener, 0)->shouldBeCalled();

        $this->addListener('test', $listener, 0);
    }

    function it_should_proxy_addOneTimeListener(EmitterInterface $emitter, ListenerInterface $listener)
    {
        $emitter->addOneTimeListener('test', $listener, 0)->shouldBeCalled();

        $this->addOneTimeListener('test', $listener, 0);
    }
}
