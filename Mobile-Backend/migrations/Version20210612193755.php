<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210612193755 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commission (id INT AUTO_INCREMENT NOT NULL, etat INT NOT NULL, transfert_argent INT NOT NULL, operateur_depot INT NOT NULL, operateur_retrait INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE depot (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, compte_id INT DEFAULT NULL, created_at DATETIME NOT NULL, montant INT NOT NULL, INDEX IDX_47948BBCA76ED395 (user_id), INDEX IDX_47948BBCF2C56620 (compte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tarif (id INT AUTO_INCREMENT NOT NULL, montant_min INT NOT NULL, montant_max INT NOT NULL, frais_envoi INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE depot ADD CONSTRAINT FK_47948BBCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE depot ADD CONSTRAINT FK_47948BBCF2C56620 FOREIGN KEY (compte_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE client CHANGE cni cni VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE compte DROP FOREIGN KEY FK_CFF65260A76ED395');
        $this->addSql('DROP INDEX IDX_CFF65260A76ED395 ON compte');
        $this->addSql('ALTER TABLE compte DROP user_id');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D119EB6921');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1A76ED395');
        $this->addSql('DROP INDEX IDX_723705D1A76ED395 ON transaction');
        $this->addSql('DROP INDEX IDX_723705D119EB6921 ON transaction');
        $this->addSql('ALTER TABLE transaction ADD user_retrait_id INT DEFAULT NULL, ADD user_depot_id INT DEFAULT NULL, ADD client_envoi_id INT DEFAULT NULL, ADD client_retrait_id INT DEFAULT NULL, ADD commission_etat DOUBLE PRECISION NOT NULL, ADD commission_transfert DOUBLE PRECISION NOT NULL, ADD commission_depot DOUBLE PRECISION NOT NULL, ADD commission_retrait DOUBLE PRECISION NOT NULL, ADD date_annulation DATETIME DEFAULT NULL, DROP user_id, DROP client_id');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1D99F8396 FOREIGN KEY (user_retrait_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1659D30DE FOREIGN KEY (user_depot_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D11171DC20 FOREIGN KEY (client_envoi_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1EEAC783B FOREIGN KEY (client_retrait_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_723705D1D99F8396 ON transaction (user_retrait_id)');
        $this->addSql('CREATE INDEX IDX_723705D1659D30DE ON transaction (user_depot_id)');
        $this->addSql('CREATE INDEX IDX_723705D11171DC20 ON transaction (client_envoi_id)');
        $this->addSql('CREATE INDEX IDX_723705D1EEAC783B ON transaction (client_retrait_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE commission');
        $this->addSql('DROP TABLE depot');
        $this->addSql('DROP TABLE tarif');
        $this->addSql('ALTER TABLE client CHANGE cni cni VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE compte ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE compte ADD CONSTRAINT FK_CFF65260A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CFF65260A76ED395 ON compte (user_id)');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1D99F8396');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1659D30DE');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D11171DC20');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1EEAC783B');
        $this->addSql('DROP INDEX IDX_723705D1D99F8396 ON transaction');
        $this->addSql('DROP INDEX IDX_723705D1659D30DE ON transaction');
        $this->addSql('DROP INDEX IDX_723705D11171DC20 ON transaction');
        $this->addSql('DROP INDEX IDX_723705D1EEAC783B ON transaction');
        $this->addSql('ALTER TABLE transaction ADD user_id INT DEFAULT NULL, ADD client_id INT DEFAULT NULL, DROP user_retrait_id, DROP user_depot_id, DROP client_envoi_id, DROP client_retrait_id, DROP commission_etat, DROP commission_transfert, DROP commission_depot, DROP commission_retrait, DROP date_annulation');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D119EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_723705D1A76ED395 ON transaction (user_id)');
        $this->addSql('CREATE INDEX IDX_723705D119EB6921 ON transaction (client_id)');
    }
}
