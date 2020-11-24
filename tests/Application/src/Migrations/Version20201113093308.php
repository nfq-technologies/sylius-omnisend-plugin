<?php

declare(strict_types=1);

namespace Tests\NFQ\SyliusOmnisendPlugin\Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201113093308 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Added pushed_to_omnisend for taxon';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE sylius_taxon ADD pushed_to_omnisend DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE sylius_taxon DROP pushed_to_omnisend');
    }
}
