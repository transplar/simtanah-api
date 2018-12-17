<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181211024844 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        // $this->addSql('ALTER TABLE topology.layer DROP CONSTRAINT layer_topology_id_fkey');
        // $this->addSql('DROP SEQUENCE topology.topology_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE news_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE pengguna_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE news (id INT NOT NULL, writer_id INT NOT NULL, title VARCHAR(255) NOT NULL, content TEXT NOT NULL, published_on TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, last_update TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1DD399501BC7E6B6 ON news (writer_id)');
        $this->addSql('CREATE TABLE pengguna (id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, fullname VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3C109A2AF85E0677 ON pengguna (username)');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD399501BC7E6B6 FOREIGN KEY (writer_id) REFERENCES pengguna (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        // $this->addSql('DROP TABLE topology.topology');
        // $this->addSql('DROP TABLE topology.layer');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        // $this->addSql('CREATE SCHEMA public');
        // $this->addSql('CREATE SCHEMA topology');
        $this->addSql('ALTER TABLE news DROP CONSTRAINT FK_1DD399501BC7E6B6');
        $this->addSql('DROP SEQUENCE news_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE pengguna_id_seq CASCADE');
        // $this->addSql('CREATE SEQUENCE topology.topology_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        // $this->addSql('CREATE TABLE topology.topology (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, srid INT NOT NULL, "precision" DOUBLE PRECISION NOT NULL, hasz BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        // $this->addSql('CREATE UNIQUE INDEX topology_name_key ON topology.topology (name)');
        // $this->addSql('CREATE TABLE topology.layer (topology_id INT NOT NULL, layer_id INT NOT NULL, schema_name VARCHAR(255) NOT NULL, table_name VARCHAR(255) NOT NULL, feature_column VARCHAR(255) NOT NULL, feature_type INT NOT NULL, level INT DEFAULT 0 NOT NULL, child_id INT DEFAULT NULL, PRIMARY KEY(topology_id, layer_id))');
        // $this->addSql('CREATE UNIQUE INDEX layer_schema_name_table_name_feature_column_key ON topology.layer (schema_name, table_name, feature_column)');
        // $this->addSql('CREATE INDEX IDX_181D8D68ED697DD5 ON topology.layer (topology_id)');
        // $this->addSql('ALTER TABLE topology.layer ADD CONSTRAINT layer_topology_id_fkey FOREIGN KEY (topology_id) REFERENCES topology.topology (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE pengguna');
    }
}
