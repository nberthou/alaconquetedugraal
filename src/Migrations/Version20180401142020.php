<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180401142020 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE carte DROP FOREIGN KEY FK_BAD4FFFD66DAD6F2');
        $this->addSql('DROP INDEX IDX_BAD4FFFD66DAD6F2 ON carte');
        $this->addSql('ALTER TABLE carte CHANGE objectifs_id objectif_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE carte ADD CONSTRAINT FK_BAD4FFFD157D1AD4 FOREIGN KEY (objectif_id) REFERENCES objectif (id)');
        $this->addSql('CREATE INDEX IDX_BAD4FFFD157D1AD4 ON carte (objectif_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE carte DROP FOREIGN KEY FK_BAD4FFFD157D1AD4');
        $this->addSql('DROP INDEX IDX_BAD4FFFD157D1AD4 ON carte');
        $this->addSql('ALTER TABLE carte CHANGE objectif_id objectifs_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE carte ADD CONSTRAINT FK_BAD4FFFD66DAD6F2 FOREIGN KEY (objectifs_id) REFERENCES objectif (id)');
        $this->addSql('CREATE INDEX IDX_BAD4FFFD66DAD6F2 ON carte (objectifs_id)');
    }
}
