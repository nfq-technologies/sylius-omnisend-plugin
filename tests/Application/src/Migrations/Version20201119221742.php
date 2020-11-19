<?php

declare(strict_types=1);

namespace Tests\NFQ\SyliusOmnisendPlugin\Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201119221742 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE sylius_product ADD pushed_to_omnisend DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE sylius_product DROP pushed_to_omnisend');
    }
}
