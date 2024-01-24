<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240123130306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tour ADD informe_id INT NOT NULL');
        $this->addSql('ALTER TABLE tour ADD CONSTRAINT FK_6AD1F96983138CEF FOREIGN KEY (informe_id) REFERENCES informe (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6AD1F96983138CEF ON tour (informe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tour DROP FOREIGN KEY FK_6AD1F96983138CEF');
        $this->addSql('DROP INDEX UNIQ_6AD1F96983138CEF ON tour');
        $this->addSql('ALTER TABLE tour DROP informe_id');
    }
}
