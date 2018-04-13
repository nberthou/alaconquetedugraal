<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180327130846 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE partie DROP FOREIGN KEY FK_59B1F3D80535810');
        $this->addSql('ALTER TABLE partie DROP FOREIGN KEY FK_59B1F3D92E6F7FE');
        $this->addSql('ALTER TABLE partie DROP FOREIGN KEY FK_59B1F3DA24E787C');
        $this->addSql('ALTER TABLE partie DROP FOREIGN KEY FK_59B1F3DB0FBD792');
        $this->addSql('DROP INDEX IDX_59B1F3D92E6F7FE ON partie');
        $this->addSql('DROP INDEX IDX_59B1F3D80535810 ON partie');
        $this->addSql('DROP INDEX IDX_59B1F3DB0FBD792 ON partie');
        $this->addSql('DROP INDEX IDX_59B1F3DA24E787C ON partie');
        $this->addSql('ALTER TABLE partie ADD secret_j1 VARCHAR(255) DEFAULT NULL, ADD secret_j2 VARCHAR(255) DEFAULT NULL, ADD dissimulation_j1 VARCHAR(255) DEFAULT NULL, ADD dissimulation_j2 VARCHAR(255) DEFAULT NULL, DROP secret_j1_id, DROP secret_j2_id, DROP dissimulation_j1_id, DROP dissimulation_j2_id');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE partie ADD secret_j1_id INT DEFAULT NULL, ADD secret_j2_id INT DEFAULT NULL, ADD dissimulation_j1_id INT DEFAULT NULL, ADD dissimulation_j2_id INT DEFAULT NULL, DROP secret_j1, DROP secret_j2, DROP dissimulation_j1, DROP dissimulation_j2');
        $this->addSql('ALTER TABLE partie ADD CONSTRAINT FK_59B1F3D80535810 FOREIGN KEY (secret_j2_id) REFERENCES carte (id)');
        $this->addSql('ALTER TABLE partie ADD CONSTRAINT FK_59B1F3D92E6F7FE FOREIGN KEY (secret_j1_id) REFERENCES carte (id)');
        $this->addSql('ALTER TABLE partie ADD CONSTRAINT FK_59B1F3DA24E787C FOREIGN KEY (dissimulation_j2_id) REFERENCES carte (id)');
        $this->addSql('ALTER TABLE partie ADD CONSTRAINT FK_59B1F3DB0FBD792 FOREIGN KEY (dissimulation_j1_id) REFERENCES carte (id)');
        $this->addSql('CREATE INDEX IDX_59B1F3D92E6F7FE ON partie (secret_j1_id)');
        $this->addSql('CREATE INDEX IDX_59B1F3D80535810 ON partie (secret_j2_id)');
        $this->addSql('CREATE INDEX IDX_59B1F3DB0FBD792 ON partie (dissimulation_j1_id)');
        $this->addSql('CREATE INDEX IDX_59B1F3DA24E787C ON partie (dissimulation_j2_id)');
    }
}
