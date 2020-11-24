<?php

/*
 * @copyright C UAB NFQ Technologies
 *
 * This Software is the property of NFQ Technologies
 * and is protected by copyright law – it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * Contact UAB NFQ Technologies:
 * E-mail: info@nfq.lt
 * http://www.nfq.lt
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
