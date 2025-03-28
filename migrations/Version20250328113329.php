<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250328113329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Добавляет поле refresh_token в таблицу пользователей';
    }

    public function isTransactional(): bool
    {
        return false;
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" ADD refresh_token VARCHAR(32) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX CONCURRENTLY UNIQ_8D93D649C74F2195 ON "user" (refresh_token)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX CONCURRENTLY UNIQ_8D93D649C74F2195');
        $this->addSql('ALTER TABLE "user" DROP refresh_token');
    }
}
