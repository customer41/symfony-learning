<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250224061625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Генерирует таблицы: навыки, задания, занятия';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE lesson (id BIGINT GENERATED BY DEFAULT AS IDENTITY NOT NULL, title VARCHAR(255) NOT NULL, target TEXT NOT NULL, description TEXT NOT NULL, results TEXT NOT NULL, teacher VARCHAR(255) DEFAULT NULL, date_time TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, duration INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE skill (id BIGINT GENERATED BY DEFAULT AS IDENTITY NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(1024) DEFAULT NULL, task_percent INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, task_id BIGINT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX skill__task_id__ind ON skill (task_id)');
        $this->addSql('CREATE TABLE task (id BIGINT GENERATED BY DEFAULT AS IDENTITY NOT NULL, title VARCHAR(255) NOT NULL, target VARCHAR(255) NOT NULL, description TEXT NOT NULL, criteria TEXT NOT NULL, submit_before TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, lesson_id BIGINT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX task__lesson_id__ind ON task (lesson_id)');
        $this->addSql('ALTER TABLE skill ADD CONSTRAINT skill__task_id__fk FOREIGN KEY (task_id) REFERENCES task (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT task__lesson_id__fk FOREIGN KEY (lesson_id) REFERENCES lesson (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE skill DROP CONSTRAINT skill__task_id__fk');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT task__lesson_id__fk');
        $this->addSql('DROP TABLE lesson');
        $this->addSql('DROP TABLE skill');
        $this->addSql('DROP TABLE task');
    }
}
