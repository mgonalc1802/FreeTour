<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240123130415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reserva ADD valoracion_id INT NOT NULL');
        $this->addSql('ALTER TABLE reserva ADD CONSTRAINT FK_188D2E3BD29AA1AC FOREIGN KEY (valoracion_id) REFERENCES valoracion (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_188D2E3BD29AA1AC ON reserva (valoracion_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reserva DROP FOREIGN KEY FK_188D2E3BD29AA1AC');
        $this->addSql('DROP INDEX UNIQ_188D2E3BD29AA1AC ON reserva');
        $this->addSql('ALTER TABLE reserva DROP valoracion_id');
    }
}
