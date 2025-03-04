<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250304115213 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Создаёт индексы на таблицы course и lesson';
    }

    public function isTransactional(): bool
    {
        return false;
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE INDEX CONCURRENTLY IF NOT EXISTS course__status__start_date__ind ON course (status, start_date) WHERE (start_date IS NOT NULL)');
        $this->addSql('CREATE INDEX CONCURRENTLY IF NOT EXISTS lesson__title__ind ON lesson (title)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX CONCURRENTLY IF EXISTS lesson__title__ind');
        $this->addSql('DROP INDEX CONCURRENTLY IF EXISTS course__status__start_date__ind');
    }
}
