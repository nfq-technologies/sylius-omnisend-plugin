<?php

declare(strict_types=1);

namespace Tests\NFQ\SyliusOmnisendPlugin\Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201118234926 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE nfq_omnisend_plugin_order_details (id INT AUTO_INCREMENT NOT NULL, order_id INT NULL, cartId VARCHAR(32) DEFAULT NULL, cancelledAt DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_FC7171E38D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE nfq_omnisend_plugin_order_details ADD CONSTRAINT FK_FC7171E38D9F6D38 FOREIGN KEY (order_id) REFERENCES sylius_order (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE nfq_omnisend_plugin_order_details');
    }
}
