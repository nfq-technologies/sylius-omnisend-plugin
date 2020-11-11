<?php

declare(strict_types=1);

namespace Tests\NFQ\SyliusOmnisendPlugin\Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201111091638 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Added field omnisend_contact_id for shop user';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE sylius_customer ADD omnisend_contact_id VARCHAR(32) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE sylius_customer DROP omnisend_contact_id');
    }
}
