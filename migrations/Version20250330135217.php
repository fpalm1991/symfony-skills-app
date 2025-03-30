<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250330135217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__user_skill_level AS SELECT id, user_id, skill_id, level FROM user_skill_level
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_skill_level
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_skill_level (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, skill_id INTEGER NOT NULL, level INTEGER NOT NULL, CONSTRAINT FK_16D996BAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_16D996BA5585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO user_skill_level (id, user_id, skill_id, level) SELECT id, user_id, skill_id, level FROM __temp__user_skill_level
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__user_skill_level
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_16D996BA5585C142 ON user_skill_level (skill_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_16D996BAA76ED395 ON user_skill_level (user_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__user_skill_level AS SELECT id, user_id, skill_id, level FROM user_skill_level
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_skill_level
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_skill_level (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, skill_id INTEGER NOT NULL, level INTEGER NOT NULL, CONSTRAINT FK_16D996BAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_16D996BA5585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO user_skill_level (id, user_id, skill_id, level) SELECT id, user_id, skill_id, level FROM __temp__user_skill_level
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__user_skill_level
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_16D996BAA76ED395 ON user_skill_level (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_16D996BA5585C142 ON user_skill_level (skill_id)
        SQL);
    }
}
