<?php

// src/Purger/MysqlOrmPurger.php
namespace App\Purger;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Purger\ORMPurgerInterface;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;

class MysqlOrmPurger implements ORMPurgerInterface
{

    private readonly ORMPurgerInterface $purger;
    private bool $disableForeignKeyChecks = false;

    public function __construct(?EntityManagerInterface $em = null,
                                array $excluded = [])
    {
        $this->purger = new ORMPurger($em, $excluded);
    }

    /**
     * Disable foreign key checks before purging. Enable foreign key checks after purging
     */
    public function setDisableForeignKeyChecks(bool $disableForeignKeyChecks): void
    {
        $this->disableForeignKeyChecks = $disableForeignKeyChecks;
    }

    public function getDisableForeignKeyChecks(): bool
    {
        return $this->disableForeignKeyChecks;
    }


    /**
     * Set the purge mode
     *
     * @param int $mode
     *
     * @return void
     */
    public function setPurgeMode($mode): void
    {
        $this->purger->setPurgeMode($mode);
    }

    /**
     * Get the purge mode
     *
     * @return int
     */
    public function getPurgeMode()
    {
        return $this->purger->getPurgeMode();
    }

    public function setEntityManager(EntityManagerInterface $em)
    {
        $this->purger->setEntityManager($em);
    }

    /**
     * Retrieve the EntityManagerInterface instance this purger instance is using.
     *
     * @return EntityManagerInterface
     */
    public function getObjectManager()
    {
        return $this->purger->getObjectManager();
    }

    /**
     * @throws Exception
     */
    public function purge()
    {
        $conn = $this->getObjectManager()->getConnection();
        /** @var \PDO $pdo */
        $pdo = $conn->getNativeConnection();
        if(!($pdo instanceof \PDO)) {
            throw new \Exception('Unsupported native connection');
        }
        $wasInTransaction = $pdo->inTransaction();

        if ($this->disableForeignKeyChecks) {
            $conn->executeStatement('SET FOREIGN_KEY_CHECKS = 0');
        }

        $this->purger->purge();

        if ($this->disableForeignKeyChecks) {
            $conn->executeStatement('SET FOREIGN_KEY_CHECKS = 1');
        }

        if ($wasInTransaction && !$pdo->inTransaction()) {
            $pdo->beginTransaction();
        }
    }
}