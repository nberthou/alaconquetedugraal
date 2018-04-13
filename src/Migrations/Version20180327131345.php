<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180327131345 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE partie DROP secret_j1, DROP secret_j2, DROP dissimulation_j1, DROP dissimulation_j2');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE partie ADD secret_j1 VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD secret_j2 VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD dissimulation_j1 VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD dissimulation_j2 VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
