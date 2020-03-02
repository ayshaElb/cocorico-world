<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200302134420 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, univers_id INT NOT NULL, name VARCHAR(64) NOT NULL, image VARCHAR(255) DEFAULT NULL, enable TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_64C19C11CF61C0B (univers_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE producer (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, social_reason VARCHAR(64) NOT NULL, siret_number VARCHAR(100) NOT NULL, address VARCHAR(64) NOT NULL, postal_code INT NOT NULL, city VARCHAR(50) NOT NULL, email VARCHAR(180) DEFAULT NULL, firstname VARCHAR(20) NOT NULL, lastname VARCHAR(20) NOT NULL, telephone VARCHAR(21) DEFAULT NULL, status VARCHAR(64) DEFAULT NULL, enable TINYINT(1) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_976449DCA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, producer_id INT NOT NULL, subcategory_id INT NOT NULL, name VARCHAR(64) NOT NULL, price INT NOT NULL, weight INT DEFAULT NULL, quantity INT NOT NULL, image VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, composition LONGTEXT DEFAULT NULL, additional_info LONGTEXT DEFAULT NULL, allergens LONGTEXT DEFAULT NULL, rate INT DEFAULT NULL, enable TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_D34A04AD89B658FE (producer_id), INDEX IDX_D34A04AD5DC6FE57 (subcategory_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subcategory (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, name VARCHAR(64) NOT NULL, image VARCHAR(255) DEFAULT NULL, enable TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_DDCA44812469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE univers (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, image VARCHAR(255) DEFAULT NULL, enable TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(20) DEFAULT NULL, lastname VARCHAR(20) DEFAULT NULL, telephone VARCHAR(50) DEFAULT NULL, address VARCHAR(64) DEFAULT NULL, postal_code INT DEFAULT NULL, city VARCHAR(50) DEFAULT NULL, enable TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C11CF61C0B FOREIGN KEY (univers_id) REFERENCES univers (id)');
        $this->addSql('ALTER TABLE producer ADD CONSTRAINT FK_976449DCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD89B658FE FOREIGN KEY (producer_id) REFERENCES producer (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD5DC6FE57 FOREIGN KEY (subcategory_id) REFERENCES subcategory (id)');
        $this->addSql('ALTER TABLE subcategory ADD CONSTRAINT FK_DDCA44812469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE subcategory DROP FOREIGN KEY FK_DDCA44812469DE2');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD89B658FE');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD5DC6FE57');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C11CF61C0B');
        $this->addSql('ALTER TABLE producer DROP FOREIGN KEY FK_976449DCA76ED395');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE producer');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE subcategory');
        $this->addSql('DROP TABLE univers');
        $this->addSql('DROP TABLE user');
    }
}
