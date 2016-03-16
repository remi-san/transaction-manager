# Transaction Manager

[![Author](https://img.shields.io/badge/author-@RemiSan-blue.svg?style=flat-square)](https://twitter.com/RemiSan)
[![Build Status](https://img.shields.io/travis/remi-san/transaction-manager/master.svg?style=flat-square)](https://travis-ci.org/remi-san/transaction-manager)
[![Quality Score](https://img.shields.io/scrutinizer/g/remi-san/transaction-manager.svg?style=flat-square)](https://scrutinizer-ci.com/g/remi-san/transaction-manager)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/remi-san/transaction-manager.svg?style=flat-square)](https://packagist.org/packages/remi-san/transaction-manager)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/remi-san/transaction-manager.svg?style=flat-square)](https://scrutinizer-ci.com/g/remi-san/transaction-manager/code-structure)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/f83d5077-8374-4fbd-a20c-b2aba6c496af/small.png)](https://insight.sensiolabs.com/projects/f83d5077-8374-4fbd-a20c-b2aba6c496af)


A simple transaction manager with a naive implementation.

It provides a common interface to manage transactions.

If you want to make a class transactional, implement the `Transactional` interface.

Transaction Manager
-------------------
Two `TransactionManager` implementations are provided allowing you to manage multiple `Transactional` classes in the same logic transaction.

- `SimpleTransactionManager` is a naive implementation preventing from beginning a transaction more than once.
- `MultipleTransactionManager` allows you to begin the transaction more than once but only commits if `commit` is called the same number of times as `beginTransaction`.

Implementations
---------------
Some `Transactional` implementations are provided:

 - `TransactionalQueuePublisher` to publish in an AMQP queue with [`Burrow`](https://github.com/Evaneos/Burrow) in a transaction
 - `DoctrineDbalTransactionManager` to deal with [`Doctrine DBAL`](https://github.com/doctrine/dbal) transactions
 - `DoctrineEntityManager` to deal with [`Doctrine ORM`](https://github.com/doctrine/doctrine2) transactions
 - `TransactionalEmitter` to emit `Events` with the [`PHP League` lib](https://github.com/thephpleague/event) in a transaction
