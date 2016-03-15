<?php

namespace RemiSan\TransactionManager\Amqp;

use Burrow\QueuePublisher;
use RemiSan\TransactionManager\Exception\TransactionException;
use RemiSan\TransactionManager\Transactional;

class TransactionalQueuePublisher implements QueuePublisher, Transactional
{
    /** @var QueuePublisher */
    private $publisher;

    /** @var array */
    private $messages;

    /** @var bool */
    private $running;

    /**
     * Constructor.
     *
     * @param QueuePublisher $publisher
     */
    public function __construct(QueuePublisher $publisher)
    {
        $this->publisher = $publisher;
        $this->messages = [];
        $this->running = false;
    }

    /**
     * {@inheritdoc}
     */
    public function publish($data, $routingKey = "")
    {
        if (!$this->running) {
            throw new TransactionException('');
        }

        $this->messages[] = [
            'data' => $data,
            'routingKey' => $routingKey
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beginTransaction()
    {
        $this->messages = [];
        $this->running = true;
    }

    /**
     * {@inheritdoc}
     */
    public function commit()
    {
        foreach ($this->messages as $message) {
            $this->publisher->publish($message['data'], $message['routingKey']);
        }
        $this->running = false;
    }

    /**
     * {@inheritdoc}
     */
    public function rollback()
    {
        $this->messages = [];
        $this->running = false;
    }
}
