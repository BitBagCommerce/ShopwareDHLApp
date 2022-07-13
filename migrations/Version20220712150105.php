<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220712150105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add sales channels and api mode to config';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE bitbag_dhl_config ADD sandbox TINYINT(1) NOT NULL, ADD sales_channel_id VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE bitbag_dhl_config DROP sandbox, DROP sales_channel_id');
    }
}
