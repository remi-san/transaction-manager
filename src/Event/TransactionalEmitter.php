<?php

namespace RemiSan\TransactionManager\Event;

use League\Event\EmitterInterface;
use League\Event\Event;
use League\Event\EventInterface;
use League\Event\GeneratorInterface;
use League\Event\ListenerProviderInterface;
use RemiSan\TransactionManager\Exception\TransactionException;
use RemiSan\TransactionManager\Transactional;

final class TransactionalEmitter implements EmitterInterface, Transactional
{
    /** @var EmitterInterface */
    private $emitter;

    /** @var array */
    private $events = [];

    /** @var bool */
    private $running;

    /**
     * Constructor.
     *
     * @param EmitterInterface $emitter
     */
    public function __construct(EmitterInterface $emitter)
    {
        $this->emitter = $emitter;
        $this->events = [];
        $this->running = false;
    }

    /**
     * {@inheritdoc}
     */
    public function emit($event)
    {
        if (!$this->running) {
            throw new TransactionException('Cannot emit outside a transaction');
        }

        return $this->addEvent($event);
    }

    /**
     * {@inheritdoc}
     */
    public function emitBatch(array $events)
    {
        $results = [];

        foreach ($events as $event) {
            $results[] = $this->emit($event);
        }

        return $results;
    }

    /**
     * {@inheritdoc}
     */
    public function emitGeneratedEvents(GeneratorInterface $generator)
    {
        $events = $generator->releaseEvents();

        return $this->emitBatch($events);
    }

    /**
     * @param $event
     *
     * @return EventInterface
     */
    private function addEvent($event)
    {
        if (is_string($event)) {
            $event = Event::named($event);
        }

        if (!$event instanceof EventInterface) {
            throw new \InvalidArgumentException(
                'Events should be provides as Event instances or string, received type: ' . gettype($event)
            );
        }

        $this->events[] = $event;

        return $event;
    }

    /**
     * {@inheritdoc}
     */
    public function beginTransaction()
    {
        $this->events = [];
        $this->running = true;
    }

    /**
     * {@inheritdoc}
     */
    public function commit()
    {
        foreach ($this->events as $event) {
            $this->emitter->emit($event);
        }

        $this->events = [];
        $this->running = false;
    }

    /**
     * {@inheritdoc}
     */
    public function rollback()
    {
        $this->events = [];
        $this->running = false;
    }

    /**
     * {@inheritdoc}
     */
    public function removeListener($event, $listener)
    {
        $this->emitter->removeListener($event, $listener);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function useListenerProvider(ListenerProviderInterface $provider)
    {
        $this->emitter->useListenerProvider($provider);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeAllListeners($event)
    {
        $this->emitter->removeAllListeners($event);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasListeners($event)
    {
        return $this->emitter->hasListeners($event);
    }

    /**
     * {@inheritdoc}
     */
    public function getListeners($event)
    {
        return $this->emitter->getListeners($event);
    }

    /**
     * {@inheritdoc}
     */
    public function addListener($event, $listener, $priority = self::P_NORMAL)
    {
        $this->emitter->addListener($event, $listener, $priority);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addOneTimeListener($event, $listener, $priority = self::P_NORMAL)
    {
        $this->emitter->addOneTimeListener($event, $listener, $priority);

        return $this;
    }
}
