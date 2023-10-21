<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231021121225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shelf (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, member_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, published BOOLEAN NOT NULL, CONSTRAINT FK_A5475BE37597D3FE FOREIGN KEY (member_id) REFERENCES member (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_A5475BE37597D3FE ON shelf (member_id)');
        $this->addSql('CREATE TABLE shelf_shoe (shelf_id INTEGER NOT NULL, shoe_id INTEGER NOT NULL, PRIMARY KEY(shelf_id, shoe_id), CONSTRAINT FK_494F3E177C12FBC0 FOREIGN KEY (shelf_id) REFERENCES shelf (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_494F3E172AD16370 FOREIGN KEY (shoe_id) REFERENCES shoe (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_494F3E177C12FBC0 ON shelf_shoe (shelf_id)');
        $this->addSql('CREATE INDEX IDX_494F3E172AD16370 ON shelf_shoe (shoe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE shelf');
        $this->addSql('DROP TABLE shelf_shoe');
    }
}
