<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200626120657 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adverts (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, author_id INT UNSIGNED NOT NULL, status_id INT NOT NULL, title VARCHAR(150) NOT NULL, content LONGTEXT NOT NULL, date DATETIME NOT NULL, updated_at DATETIME NOT NULL, code VARCHAR(64) NOT NULL, INDEX IDX_8C88E77712469DE2 (category_id), INDEX IDX_8C88E777F675F31B (author_id), INDEX IDX_8C88E7776BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE adverts_tags (advert_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_4AD33C1CD07ECCB6 (advert_id), INDEX IDX_4AD33C1CBAD26311 (tag_id), PRIMARY KEY(advert_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(45) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, content VARCHAR(45) NOT NULL, updated_at DATETIME NOT NULL, code VARCHAR(45) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT UNSIGNED AUTO_INCREMENT NOT NULL, userdata_id INT NOT NULL, username VARCHAR(45) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9AB945D82 (userdata_id), UNIQUE INDEX username_idx (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_data (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(45) NOT NULL, lastname VARCHAR(45) NOT NULL, email VARCHAR(180) NOT NULL, UNIQUE INDEX UNIQ_627ABD6DE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adverts ADD CONSTRAINT FK_8C88E77712469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE adverts ADD CONSTRAINT FK_8C88E777F675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE adverts ADD CONSTRAINT FK_8C88E7776BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE adverts_tags ADD CONSTRAINT FK_4AD33C1CD07ECCB6 FOREIGN KEY (advert_id) REFERENCES adverts (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adverts_tags ADD CONSTRAINT FK_4AD33C1CBAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9AB945D82 FOREIGN KEY (userdata_id) REFERENCES users_data (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adverts_tags DROP FOREIGN KEY FK_4AD33C1CD07ECCB6');
        $this->addSql('ALTER TABLE adverts DROP FOREIGN KEY FK_8C88E77712469DE2');
        $this->addSql('ALTER TABLE adverts DROP FOREIGN KEY FK_8C88E7776BF700BD');
        $this->addSql('ALTER TABLE adverts_tags DROP FOREIGN KEY FK_4AD33C1CBAD26311');
        $this->addSql('ALTER TABLE adverts DROP FOREIGN KEY FK_8C88E777F675F31B');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9AB945D82');
        $this->addSql('DROP TABLE adverts');
        $this->addSql('DROP TABLE adverts_tags');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE users_data');
    }
}
