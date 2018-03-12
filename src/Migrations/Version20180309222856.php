<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180309222856 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ami (id INT AUTO_INCREMENT NOT NULL, ami_j1_id INT DEFAULT NULL, ami_j2_id INT DEFAULT NULL, ami_status SMALLINT NOT NULL, ami_derniere_action INT NOT NULL, INDEX IDX_5269B41371C6B3D (ami_j1_id), INDEX IDX_5269B41315A9C4D3 (ami_j2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ami ADD CONSTRAINT FK_5269B41371C6B3D FOREIGN KEY (ami_j1_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ami ADD CONSTRAINT FK_5269B41315A9C4D3 FOREIGN KEY (ami_j2_id) REFERENCES user (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE ami');
    }
}
