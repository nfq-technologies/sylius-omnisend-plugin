<?php

declare(strict_types=1);

namespace Tests\NFQ\SyliusOmnisendPlugin\Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201123015239 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE nfq_omnisend_plugin_event_fields (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, type VARCHAR(32) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, systemName VARCHAR(255) DEFAULT NULL, required TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_6666149271F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nfq_omnisend_plugin_events (id INT AUTO_INCREMENT NOT NULL, eventID VARCHAR(32) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, systemName VARCHAR(255) DEFAULT NULL, enabled TINYINT(1) DEFAULT \'1\' NOT NULL, createdAt DATETIME DEFAULT NULL, updatedAt DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE nfq_omnisend_plugin_event_fields ADD CONSTRAINT FK_6666149271F7E88B FOREIGN KEY (event_id) REFERENCES nfq_omnisend_plugin_events (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nfq_omnisend_plugin_event_fields DROP FOREIGN KEY FK_6666149271F7E88B');
        $this->addSql('DROP TABLE nfq_omnisend_plugin_event_fields');
        $this->addSql('DROP TABLE nfq_omnisend_plugin_events');
    }
}
