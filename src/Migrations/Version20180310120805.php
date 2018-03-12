<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180310120805 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ami DROP FOREIGN KEY FK_5269B41315A9C4D3');
        $this->addSql('ALTER TABLE ami DROP FOREIGN KEY FK_5269B41371C6B3D');
        $this->addSql('DROP INDEX IDX_5269B41371C6B3D ON ami');
        $this->addSql('DROP INDEX IDX_5269B41315A9C4D3 ON ami');
        $this->addSql('ALTER TABLE ami ADD j1_id INT DEFAULT NULL, ADD j2_id INT DEFAULT NULL, DROP ami_j1_id, DROP ami_j2_id, CHANGE ami_statut statut SMALLINT NOT NULL, CHANGE ami_derniere_action derniere_action INT NOT NULL');
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
        $this->addSql('ALTER TABLE ami ADD ami_j1_id INT DEFAULT NULL, ADD ami_j2_id INT DEFAULT NULL, DROP j1_id, DROP j2_id, CHANGE statut ami_statut SMALLINT NOT NULL, CHANGE derniere_action ami_derniere_action INT NOT NULL');
        $this->addSql('ALTER TABLE ami ADD CONSTRAINT FK_5269B41315A9C4D3 FOREIGN KEY (ami_j2_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ami ADD CONSTRAINT FK_5269B41371C6B3D FOREIGN KEY (ami_j1_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5269B41371C6B3D ON ami (ami_j1_id)');
        $this->addSql('CREATE INDEX IDX_5269B41315A9C4D3 ON ami (ami_j2_id)');
    }
}
