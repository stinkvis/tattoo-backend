<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250215235900 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create core tables for tattoo discovery platform';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE users (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, status VARCHAR(20) NOT NULL DEFAULT \'pending\', email_verified_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_login_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE artist_profiles (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', display_name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, bio LONGTEXT DEFAULT NULL, profile_image_url VARCHAR(255) DEFAULT NULL, cover_image_url VARCHAR(255) DEFAULT NULL, website_url VARCHAR(255) DEFAULT NULL, instagram_handle VARCHAR(255) DEFAULT NULL, contact_preference VARCHAR(20) DEFAULT NULL, is_verified TINYINT(1) DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_ARTIST_PROFILES_SLUG (slug), UNIQUE INDEX UNIQ_ARTIST_PROFILES_USER (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE studios (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, bio LONGTEXT DEFAULT NULL, logo_url VARCHAR(255) DEFAULT NULL, cover_image_url VARCHAR(255) DEFAULT NULL, phone VARCHAR(50) DEFAULT NULL, email VARCHAR(180) DEFAULT NULL, website_url VARCHAR(255) DEFAULT NULL, instagram_handle VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_STUDIOS_SLUG (slug), INDEX IDX_STUDIOS_NAME (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE studio_locations (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', studio_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', address_line1 VARCHAR(255) DEFAULT NULL, address_line2 VARCHAR(255) DEFAULT NULL, city VARCHAR(120) NOT NULL, postal_code VARCHAR(20) DEFAULT NULL, country_code CHAR(2) NOT NULL, lat NUMERIC(9, 6) DEFAULT NULL, lng NUMERIC(9, 6) DEFAULT NULL, is_primary TINYINT(1) DEFAULT 1 NOT NULL, is_active TINYINT(1) DEFAULT 1 NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_STUDIO_LOCATIONS_CITY_COUNTRY (city, country_code), INDEX IDX_STUDIO_LOCATIONS_LAT_LNG (lat, lng), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE studio_artists (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', studio_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', artist_profile_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', role VARCHAR(20) NOT NULL DEFAULT \'resident\', is_primary TINYINT(1) DEFAULT 0 NOT NULL, start_date DATE DEFAULT NULL, end_date DATE DEFAULT NULL, is_active TINYINT(1) DEFAULT 1 NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_STUDIO_ARTIST (studio_id, artist_profile_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE styles (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, is_active TINYINT(1) DEFAULT 1 NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_STYLES_NAME (name), UNIQUE INDEX UNIQ_STYLES_SLUG (slug), INDEX IDX_STYLES_NAME (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE artist_styles (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', artist_profile_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', style_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_ARTIST_STYLE (artist_profile_id, style_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE artist_profiles ADD CONSTRAINT FK_ARTIST_PROFILES_USER FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE studio_locations ADD CONSTRAINT FK_STUDIO_LOCATIONS_STUDIO FOREIGN KEY (studio_id) REFERENCES studios (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE studio_artists ADD CONSTRAINT FK_STUDIO_ARTISTS_STUDIO FOREIGN KEY (studio_id) REFERENCES studios (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE studio_artists ADD CONSTRAINT FK_STUDIO_ARTISTS_ARTIST_PROFILE FOREIGN KEY (artist_profile_id) REFERENCES artist_profiles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE artist_styles ADD CONSTRAINT FK_ARTIST_STYLES_ARTIST_PROFILE FOREIGN KEY (artist_profile_id) REFERENCES artist_profiles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE artist_styles ADD CONSTRAINT FK_ARTIST_STYLES_STYLE FOREIGN KEY (style_id) REFERENCES styles (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE artist_profiles DROP FOREIGN KEY FK_ARTIST_PROFILES_USER');
        $this->addSql('ALTER TABLE studio_locations DROP FOREIGN KEY FK_STUDIO_LOCATIONS_STUDIO');
        $this->addSql('ALTER TABLE studio_artists DROP FOREIGN KEY FK_STUDIO_ARTISTS_STUDIO');
        $this->addSql('ALTER TABLE studio_artists DROP FOREIGN KEY FK_STUDIO_ARTISTS_ARTIST_PROFILE');
        $this->addSql('ALTER TABLE artist_styles DROP FOREIGN KEY FK_ARTIST_STYLES_ARTIST_PROFILE');
        $this->addSql('ALTER TABLE artist_styles DROP FOREIGN KEY FK_ARTIST_STYLES_STYLE');

        $this->addSql('DROP TABLE artist_styles');
        $this->addSql('DROP TABLE styles');
        $this->addSql('DROP TABLE studio_artists');
        $this->addSql('DROP TABLE studio_locations');
        $this->addSql('DROP TABLE studios');
        $this->addSql('DROP TABLE artist_profiles');
        $this->addSql('DROP TABLE users');
    }
}
