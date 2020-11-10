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

namespace Tests\NFQ\SyliusOmnisendPlugin\Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201109235721 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Added omnisend_tracking_key field for channel';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE sylius_channel ADD omnisend_tracking_key VARCHAR(32) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE sylius_channel DROP omnisend_tracking_key');
    }
}
