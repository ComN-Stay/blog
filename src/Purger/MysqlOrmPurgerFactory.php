<?php

// src/Purger/MysqlOrmPurgerFactory.php
namespace App\Purger;

use Doctrine\Bundle\FixturesBundle\Purger\PurgerFactory;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Purger\PurgerInterface;
use Doctrine\ORM\EntityManagerInterface;

class MysqlOrmPurgerFactory implements PurgerFactory
{
    public function __construct(private readonly bool $disableForeignKeyChecks = false) {
    }

    /**
     * {@inheritDoc}
     */
    public function createForEntityManager(
        ?string $emName,
        EntityManagerInterface $em,
        array $excluded = [],
        bool $purgeWithTruncate = false
    ): PurgerInterface
    {
        $purger = new MysqlOrmPurger($em, $excluded);
        $purger->setPurgeMode(
            $purgeWithTruncate ? ORMPurger::PURGE_MODE_TRUNCATE : ORMPurger::PURGE_MODE_DELETE);
        $purger->setDisableForeignKeyChecks($this->disableForeignKeyChecks);
        return $purger;
    }
}