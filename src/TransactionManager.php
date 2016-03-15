<?php

namespace RemiSan\TransactionManager;

interface TransactionManager extends Transactional
{
    /**
     * Add a transactional item to the transaction manager.
     *
     * @param Transactional $item
     */
    public function addTransactionalItem(Transactional $item);
}
