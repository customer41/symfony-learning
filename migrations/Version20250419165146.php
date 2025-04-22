<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250419165146 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Добавляет поле is_required в таблицу skill';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE skill ADD is_required BOOLEAN NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE skill DROP is_required');
    }
}
