<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180524152209 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO category (id, category_name) VALUES 
            (1, "Nuosavybės dokumentai"),
            (2, "Sutartys"),
            (3, "Draudimo polisai"),
            (4, "Pažymos"),
            (5, "Mokesčiai"),
            (6, "Kvitai"),
            (7, "Instrukcijos ir garantijos"),
            (8, "Įvairūs")
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM category WHERE ID IN (1, 2, 3, 4, 5, 6, 7, 8)');
    }
}
