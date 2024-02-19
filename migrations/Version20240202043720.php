<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240202043720 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE companies (name VARCHAR(255) NOT NULL, PRIMARY KEY(name))');
        $this->addSql('CREATE TABLE departments (uuid UUID NOT NULL, company_name VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, type enum_bonus_types NOT NULL, increase_factor INT NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_16AEB8D41D4E64E8 ON departments (company_name)');
        $this->addSql('COMMENT ON COLUMN departments.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN departments.type IS \'(DC2Type:enum_bonus_types)\'');
        $this->addSql('CREATE TABLE employees (uuid UUID NOT NULL, department_uuid UUID DEFAULT NULL, start_date DATE NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, amount INT NOT NULL, currency enum_currencies NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_BA82C300736537F3 ON employees (department_uuid)');
        $this->addSql('COMMENT ON COLUMN employees.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN employees.department_uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN employees.currency IS \'(DC2Type:enum_currencies)\'');
        $this->addSql('CREATE TABLE payroll_report_parts (uuid UUID NOT NULL, payroll_report_uuid UUID DEFAULT NULL, base_salary_amount INT NOT NULL, additional_to_base_salary_amount INT NOT NULL, bonus_type enum_bonus_types NOT NULL, salary_with_bonus INT NOT NULL, employee_first_name VARCHAR(255) NOT NULL, employee_last_name VARCHAR(255) NOT NULL, department_name VARCHAR(255) NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_FDA194D25477F82F ON payroll_report_parts (payroll_report_uuid)');
        $this->addSql('COMMENT ON COLUMN payroll_report_parts.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN payroll_report_parts.payroll_report_uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN payroll_report_parts.bonus_type IS \'(DC2Type:enum_bonus_types)\'');
        $this->addSql('CREATE TABLE payroll_reports (uuid UUID NOT NULL, company_name VARCHAR(255) DEFAULT NULL, date DATE NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_6798B3F21D4E64E8 ON payroll_reports (company_name)');
        $this->addSql('COMMENT ON COLUMN payroll_reports.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE departments ADD CONSTRAINT FK_16AEB8D41D4E64E8 FOREIGN KEY (company_name) REFERENCES companies (name) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE employees ADD CONSTRAINT FK_BA82C300736537F3 FOREIGN KEY (department_uuid) REFERENCES departments (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payroll_report_parts ADD CONSTRAINT FK_FDA194D25477F82F FOREIGN KEY (payroll_report_uuid) REFERENCES payroll_reports (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payroll_reports ADD CONSTRAINT FK_6798B3F21D4E64E8 FOREIGN KEY (company_name) REFERENCES companies (name) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE departments DROP CONSTRAINT FK_16AEB8D41D4E64E8');
        $this->addSql('ALTER TABLE employees DROP CONSTRAINT FK_BA82C300736537F3');
        $this->addSql('ALTER TABLE payroll_report_parts DROP CONSTRAINT FK_FDA194D25477F82F');
        $this->addSql('ALTER TABLE payroll_reports DROP CONSTRAINT FK_6798B3F21D4E64E8');
        $this->addSql('DROP TABLE companies');
        $this->addSql('DROP TABLE departments');
        $this->addSql('DROP TABLE employees');
        $this->addSql('DROP TABLE payroll_report_parts');
        $this->addSql('DROP TABLE payroll_reports');
    }
}
