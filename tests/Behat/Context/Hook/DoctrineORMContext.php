<?php

/*
 * This file is part of the NFQ package.
 *
 * (c) Nfq Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\NFQ\SyliusOmnisendPlugin\Behat\Context\Hook;

use Behat\Behat\Context\Context;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\AbstractMySQLDriver;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineORMContext implements Context
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @BeforeScenario
     */
    public function purgeDatabase(): void
    {
        $this->entityManager->getConnection()->getConfiguration()->setSQLLogger(null);

        if ($this->isMysql()) {
            $this->disableForeignKeyChecks();
        }

        $purger = new ORMPurger($this->entityManager);
        $purger->purge();
        $this->entityManager->clear();

        if ($this->isMysql()) {
            $this->enableForeignKeyChecks();
        }
    }

    /**
     * @return bool
     */
    private function isMysql(): bool
    {
        return $this->entityManager->getConnection()->getDriver() instanceof AbstractMySQLDriver;
    }

    /**
     * @throws DBALException
     */
    private function disableForeignKeyChecks(): void
    {
        $this->entityManager->getConnection()->exec('SET FOREIGN_KEY_CHECKS=0');
    }

    /**
     * @throws DBALException
     */
    private function enableForeignKeyChecks(): void
    {
        $this->entityManager->getConnection()->exec('SET FOREIGN_KEY_CHECKS=1');
    }
}
