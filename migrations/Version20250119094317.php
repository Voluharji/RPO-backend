<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250119094317 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tag CHANGE tag_name name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE tag_product DROP FOREIGN KEY FK_E17B29074584665A');
        $this->addSql('ALTER TABLE tag_product DROP FOREIGN KEY FK_E17B2907BAD26311');
        $this->addSql('ALTER TABLE tag_product ADD tagproduct_id INT AUTO_INCREMENT NOT NULL, CHANGE product_id product_id INT DEFAULT NULL, CHANGE tag_id tag_id INT DEFAULT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (tagproduct_id)');
        $this->addSql('ALTER TABLE tag_product ADD CONSTRAINT FK_E17B29074584665A FOREIGN KEY (product_id) REFERENCES product (product_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_product ADD CONSTRAINT FK_E17B2907BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (tag_id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_E17B29074584665A ON tag_product (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tag CHANGE name tag_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE tag_product MODIFY tagproduct_id INT NOT NULL');
        $this->addSql('ALTER TABLE tag_product DROP FOREIGN KEY FK_E17B29074584665A');
        $this->addSql('ALTER TABLE tag_product DROP FOREIGN KEY FK_E17B2907BAD26311');
        $this->addSql('DROP INDEX IDX_E17B29074584665A ON tag_product');
        $this->addSql('DROP INDEX `PRIMARY` ON tag_product');
        $this->addSql('ALTER TABLE tag_product DROP tagproduct_id, CHANGE product_id product_id INT AUTO_INCREMENT NOT NULL, CHANGE tag_id tag_id INT NOT NULL');
        $this->addSql('ALTER TABLE tag_product ADD CONSTRAINT FK_E17B29074584665A FOREIGN KEY (product_id) REFERENCES product (product_id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE tag_product ADD CONSTRAINT FK_E17B2907BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (tag_id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE tag_product ADD PRIMARY KEY (product_id)');
    }
}
