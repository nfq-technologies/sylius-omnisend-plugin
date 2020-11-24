<?php

declare(strict_types=1);

namespace Tests\NFQ\SyliusOmnisendPlugin\Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201112132447 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Added omnisend_api_key';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE sylius_channel ADD omnisend_api_key VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE sylius_channel DROP omnisend_api_key');
    }
}
