<?php

declare(strict_types=1);

namespace Tests\NFQ\SyliusOmnisendPlugin\Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201123114536 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nfq_omnisend_plugin_events ADD channel_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE nfq_omnisend_plugin_events ADD CONSTRAINT FK_419009B172F5A1AA FOREIGN KEY (channel_id) REFERENCES sylius_channel (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_419009B172F5A1AA ON nfq_omnisend_plugin_events (channel_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nfq_omnisend_plugin_events DROP FOREIGN KEY FK_419009B172F5A1AA');
        $this->addSql('DROP INDEX IDX_419009B172F5A1AA ON nfq_omnisend_plugin_events');
        $this->addSql('ALTER TABLE nfq_omnisend_plugin_events DROP channel_id');
    }
}
