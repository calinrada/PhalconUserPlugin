CREATE SCHEMA IF NOT EXISTS public;
SET SEARCH_PATH TO PUBLIC;

CREATE TABLE IF NOT EXISTS locations (
  id                BIGSERIAL PRIMARY KEY,
  language          CHAR(2)            DEFAULT NULL,
  formatted_address VARCHAR(160)       DEFAULT NULL,
  city              VARCHAR(100)       DEFAULT NULL,
  country           VARCHAR(100)       DEFAULT NULL,
  latitude          REAL               DEFAULT NULL,
  longitude         REAL               DEFAULT NULL,
  geo_point         POINT              DEFAULT NULL,
  created_at        TIMESTAMP NOT NULL DEFAULT NOW(),
  updated_at        TIMESTAMP          DEFAULT NULL
) WITHOUT OIDS;


-- --------------------------------------------------------

--
-- Table structure for table user_groups
--
CREATE TABLE IF NOT EXISTS user_groups (
  id     SERIAL PRIMARY KEY,
  name   VARCHAR(64) NOT NULL,
  active BOOLEAN     NOT NULL
) WITHOUT OIDS;
-- --------------------------------------------------------

--
-- Table structure for table user
--
CREATE TABLE IF NOT EXISTS users (
  id                   BIGSERIAL PRIMARY KEY,
  group_id             SMALLINT REFERENCES user_groups (id),
  name                 VARCHAR(64)           DEFAULT NULL,
  first_name           VARCHAR(32)           DEFAULT NULL,
  last_name            VARCHAR(32)           DEFAULT NULL,
  email                VARCHAR(48)  NOT NULL,
  password             VARCHAR(128) NOT NULL,
  facebook_id          VARCHAR(20)           DEFAULT NULL,
  facebook_name        VARCHAR(64)           DEFAULT NULL,
  facebook_data        TEXT,
  linkedin_id          BIGINT                DEFAULT NULL,
  linkedin_name        VARCHAR(64)           DEFAULT NULL,
  linkedin_data        TEXT,
  gplus_id             VARCHAR(100)          DEFAULT NULL,
  gplus_name           VARCHAR(64)           DEFAULT NULL,
  gplus_data           TEXT,
  twitter_id           VARCHAR(20)           DEFAULT NULL,
  twitter_name         VARCHAR(64)           DEFAULT NULL,
  twitter_data         TEXT,
  must_change_password BOOLEAN               DEFAULT NULL,
  status               INT          NOT NULL DEFAULT 0, -- 0 inactive, -1 banned, -2 suspended, 1 active, -3 deleted
  created_at           TIMESTAMP    NOT NULL DEFAULT NOW(),
  updated_at           TIMESTAMP             DEFAULT NULL
) WITHOUT OIDS;


CREATE TABLE IF NOT EXISTS user_profile (
  id                  BIGSERIAL PRIMARY KEY,
  user_id             BIGINT REFERENCES users (id),
  picture             VARCHAR(255)       DEFAULT NULL,
  birth_date          DATE               DEFAULT NULL,
  gender              BOOLEAN            DEFAULT NULL,
  home_location_id    BIGINT             DEFAULT NULL REFERENCES locations (id),
  current_location_id BIGINT             DEFAULT NULL REFERENCES locations (id),
  created_at          TIMESTAMP NOT NULL DEFAULT NOW(),
  updated_at          TIMESTAMP          DEFAULT NULL
) WITHOUT OIDS;

-- --------------------------------------------------------

--
-- Table structure for table user_email_confirmations
--
CREATE TABLE IF NOT EXISTS user_email_confirmations (
  id         BIGSERIAL PRIMARY KEY,
  user_id    BIGINT REFERENCES users (id),
  code       CHAR(32)  NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT NOW(),
  updated_at TIMESTAMP          DEFAULT NULL,
  confirmed  BOOLEAN            DEFAULT FALSE
) WITHOUT OIDS;

-- --------------------------------------------------------

--
-- Table structure for table user_failed_logins
--
CREATE TABLE IF NOT EXISTS user_failed_logins (
  id         BIGSERIAL PRIMARY KEY,
  user_id    BIGINT REFERENCES users (id),
  ip_address INET   NOT NULL,
  attempted  BIGINT NOT NULL
) WITHOUT OIDS;


-- --------------------------------------------------------

--
-- Table structure for table user_notifications
--
CREATE TABLE IF NOT EXISTS user_notifications (
  id            BIGSERIAL PRIMARY KEY,
  user_id       BIGINT REFERENCES users (id),
  object_id     BIGINT    NOT NULL,
  object_source VARCHAR(30)        DEFAULT NULL,
  content       TEXT      NOT NULL,
  is_seen       BOOLEAN            DEFAULT FALSE,
  created_at    TIMESTAMP NOT NULL DEFAULT NOW()
) WITHOUT OIDS;

-- --------------------------------------------------------

--
-- Table structure for table user_password_changes
--
CREATE TABLE IF NOT EXISTS user_password_changes (
  id         BIGSERIAL PRIMARY KEY,
  user_id    BIGINT REFERENCES users (id),
  ip_address INET         NOT NULL,
  user_agent VARCHAR(255) NOT NULL,
  created_at TIMESTAMP    NOT NULL DEFAULT NOW()
) WITHOUT OIDS;

-- --------------------------------------------------------

--
-- Table structure for table user_permissions
--
CREATE TABLE IF NOT EXISTS user_permissions (
  id       BIGSERIAL PRIMARY KEY,
  group_id SMALLINT REFERENCES user_groups (id),
  resource VARCHAR(16) NOT NULL,
  action   VARCHAR(16) NOT NULL
) WITHOUT OIDS;

-- --------------------------------------------------------

--
-- Table structure for table user_profile
--
CREATE TABLE IF NOT EXISTS user_profile (
  id                  BIGSERIAL PRIMARY KEY,
  user_id             BIGINT REFERENCES users (id),
  picture             VARCHAR(255) DEFAULT NULL,
  birth_date          DATE         DEFAULT NULL,
  gender              BOOLEAN      DEFAULT NULL,
  home_location_id    BIGINT       DEFAULT NULL REFERENCES locations (id),
  current_location_id BIGINT       DEFAULT NULL REFERENCES locations (id),
  created_at          TIMESTAMP    NOT NULL DEFAULT NOW(),
  updated_at          TIMESTAMP    DEFAULT NULL
) WITHOUT OIDS;

-- --------------------------------------------------------

--
-- Table structure for table user_remember_tokens
--
CREATE TABLE IF NOT EXISTS user_remember_tokens (
  id         BIGSERIAL PRIMARY KEY,
  user_id    BIGINT REFERENCES users (id),
  token      CHAR(32) NOT NULL,
  user_agent VARCHAR(255)      DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT NOW()
) WITHOUT OIDS;

-- --------------------------------------------------------

--
-- Table structure for table user_reset_passwords
--
CREATE TABLE IF NOT EXISTS user_reset_passwords (
  id          BIGSERIAL PRIMARY KEY,
  user_id     BIGINT REFERENCES users (id),
  code        VARCHAR(48) NOT NULL,
  reset       BOOLEAN     NOT NULL,
  created_at  TIMESTAMP   NOT NULL DEFAULT NOW(),
  modified_at TIMESTAMP            DEFAULT NULL
) WITHOUT OIDS;

-- --------------------------------------------------------

--
-- Table structure for table user_success_logins
--
CREATE TABLE IF NOT EXISTS user_success_logins (
  id         BIGSERIAL PRIMARY KEY,
  user_id    BIGINT REFERENCES users (id),
  ip_address INET         NOT NULL,
  user_agent VARCHAR(255) NOT NULL,
  created_at TIMESTAMP    NOT NULL DEFAULT NOW()
) WITHOUT OIDS;

DROP INDEX IF EXISTS idx_city_country_formatted_address;
DROP INDEX IF EXISTS idx_facebook_id;
DROP INDEX IF EXISTS idx_linkedin_id;
DROP INDEX IF EXISTS idx_gplus_id;
DROP INDEX IF EXISTS idx_name;
DROP INDEX IF EXISTS idx_active;
DROP INDEX IF EXISTS idx_token;

CREATE INDEX idx_city_country_formatted_address ON locations (city, country, formatted_address);
CREATE INDEX idx_facebook_id ON users (facebook_id, facebook_name);
CREATE INDEX idx_linkedin_id ON users (linkedin_id, linkedin_name);
CREATE INDEX idx_gplus_id ON users (gplus_id, gplus_name, twitter_id, twitter_name);
CREATE INDEX idx_name ON users (name);
CREATE INDEX idx_active ON user_groups (active);
CREATE INDEX idx_token ON user_remember_tokens (token);
