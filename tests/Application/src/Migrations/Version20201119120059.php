<?php

declare(strict_types=1);

namespace Tests\NFQ\SyliusOmnisendPlugin\Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201119120059 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE sylius_order ADD omnisend_order_details_id INT DEFAULT NULL, DROP omnisend_cart_id');
        $this->addSql('ALTER TABLE sylius_order ADD CONSTRAINT FK_6196A1F9C874952A FOREIGN KEY (omnisend_order_details_id) REFERENCES nfq_omnisend_plugin_order_details (id) ON DELETE CASCADE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6196A1F9C874952A ON sylius_order (omnisend_order_details_id)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE sylius_order DROP FOREIGN KEY FK_6196A1F9C874952A');
        $this->addSql('DROP INDEX UNIQ_6196A1F9C874952A ON sylius_order');
        $this->addSql('ALTER TABLE sylius_order ADD omnisend_cart_id VARCHAR(32) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, ADD cancelled_at DATETIME DEFAULT NULL, DROP omnisend_order_details_id');
    }
}
