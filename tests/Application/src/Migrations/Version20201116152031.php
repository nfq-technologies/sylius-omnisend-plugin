<?php

declare(strict_types=1);

namespace Tests\NFQ\SyliusOmnisendPlugin\Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201116152031 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Added omnisend_cart_id field for cart.';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE sylius_order ADD omnisend_cart_id VARCHAR(32) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE sylius_order DROP omnisend_cart_id');
    }
}
