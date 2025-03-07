<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250305135447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Добавляет поле deleted_at для основных бизнес-сущностей';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE course ADD deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE lesson ADD deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE module ADD deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE skill ADD deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE task ADD deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE lesson DROP deleted_at');
        $this->addSql('ALTER TABLE module DROP deleted_at');
        $this->addSql('ALTER TABLE skill DROP deleted_at');
        $this->addSql('ALTER TABLE course DROP deleted_at');
        $this->addSql('ALTER TABLE task DROP deleted_at');
    }
}
