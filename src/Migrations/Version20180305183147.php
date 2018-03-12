<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180305183147 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE carte (id INT AUTO_INCREMENT NOT NULL, objectif_id INT DEFAULT NULL, nom LONGTEXT NOT NULL, points INT NOT NULL, img LONGTEXT NOT NULL, INDEX IDX_BAD4FFFD157D1AD4 (objectif_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE objectif (id INT AUTO_INCREMENT NOT NULL, img LONGTEXT NOT NULL, nbpoints INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partie (id INT AUTO_INCREMENT NOT NULL, j1_id INT DEFAULT NULL, j2_id INT DEFAULT NULL, partie_nom LONGTEXT NOT NULL, main_j1 LONGTEXT NOT NULL, main_j2 LONGTEXT NOT NULL, score_j1 INT NOT NULL, score_j2 INT NOT NULL, tour INT NOT NULL, manche INT NOT NULL, pioche LONGTEXT NOT NULL, objectifs LONGTEXT NOT NULL, carte_jetee LONGTEXT NOT NULL, action_j1 LONGTEXT NOT NULL, action_j2 LONGTEXT NOT NULL, terrain_j1 LONGTEXT NOT NULL, terrain_j2 LONGTEXT NOT NULL, INDEX IDX_59B1F3DC4371B17 (j1_id), INDEX IDX_59B1F3DD682B4F9 (j2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE carte ADD CONSTRAINT FK_BAD4FFFD157D1AD4 FOREIGN KEY (objectif_id) REFERENCES objectif (id)');
        $this->addSql('ALTER TABLE partie ADD CONSTRAINT FK_59B1F3DC4371B17 FOREIGN KEY (j1_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE partie ADD CONSTRAINT FK_59B1F3DD682B4F9 FOREIGN KEY (j2_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user CHANGE banned banned TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE carte DROP FOREIGN KEY FK_BAD4FFFD157D1AD4');
        $this->addSql('DROP TABLE carte');
        $this->addSql('DROP TABLE objectif');
        $this->addSql('DROP TABLE partie');
        $this->addSql('ALTER TABLE user CHANGE banned banned TINYINT(1) NOT NULL');
    }
}
