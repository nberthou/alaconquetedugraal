<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180310125858 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ami ADD j1_id INT DEFAULT NULL, ADD j2_id INT DEFAULT NULL, DROP j1, DROP j2');
        $this->addSql('ALTER TABLE ami ADD CONSTRAINT FK_5269B413C4371B17 FOREIGN KEY (j1_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ami ADD CONSTRAINT FK_5269B413D682B4F9 FOREIGN KEY (j2_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5269B413C4371B17 ON ami (j1_id)');
        $this->addSql('CREATE INDEX IDX_5269B413D682B4F9 ON ami (j2_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ami DROP FOREIGN KEY FK_5269B413C4371B17');
        $this->addSql('ALTER TABLE ami DROP FOREIGN KEY FK_5269B413D682B4F9');
        $this->addSql('DROP INDEX IDX_5269B413C4371B17 ON ami');
        $this->addSql('DROP INDEX IDX_5269B413D682B4F9 ON ami');
        $this->addSql('ALTER TABLE ami ADD j1 INT NOT NULL, ADD j2 INT NOT NULL, DROP j1_id, DROP j2_id');
    }
}
