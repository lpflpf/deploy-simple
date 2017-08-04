-- noinspection SqlSignatureForFile

-- deploy.db

----------------command
DROP TABLE IF EXISTS command;
CREATE TABLE command (
  id                INTEGER PRIMARY KEY AUTOINCREMENT,
  name              TEXT    NOT NULL,
  type              INT     NOT NULL,
  context           BLOB    NOT NULL,
  is_delete         INTEGER NOT NULL    DEFAULT 0,
  creation_date     DATE    NOT NULL,
  last_changed_date DATE    NOT NULL
);

----------------command_params
DROP TABLE IF EXISTS command_params;
CREATE TABLE command_params (
  id                INTEGER PRIMARY KEY AUTOINCREMENT,
  command_id        INTEGER,
  idx               INTEGER  NOT NULL,
  name              TEXT     NOT NULL, -- name
  is_escape         SMALLINT NOT NULL   DEFAULT 0,
  is_delete         INTEGER  NOT NULL   DEFAULT 0,
  creation_date     DATE     NOT NULL,
  last_changed_date DATE     NOT NULL,
  FOREIGN KEY (command_id) REFERENCES command (id)
);
--
DROP TABLE IF EXISTS cluster;
CREATE TABLE cluster (
  id                INTEGER PRIMARY KEY AUTOINCREMENT,
  name              TEXT    NOT NULL,
  is_delete         INTEGER NOT NULL    DEFAULT 0,
  creation_date     DATE    NOT NULL,
  last_changed_date DATE    NOT NULL
);
DROP TABLE IF EXISTS server;
CREATE TABLE server (
  id                INTEGER PRIMARY KEY AUTOINCREMENT,
  cluster_id        INTEGER NOT NULL,
  ip                TEXT    NOT NULL,
  is_delete         INTEGER NOT NULL    DEFAULT 0,
  creation_date     DATE    NOT NULL,
  last_changed_date DATE    NOT NULL,
  FOREIGN KEY (cluster_id) REFERENCES cluster (id)
);

-- flow

DROP TABLE IF EXISTS flow;

CREATE TABLE flow (
  id                INTEGER PRIMARY KEY AUTOINCREMENT,
  name              TEXT    NOT NULL,
  is_delete         INTEGER NOT NULL    DEFAULT 0,
  creation_date     DATE    NOT NULL,
  last_changed_date DATE    NOT NULL
);

-- exec_record table.

DROP TABLE IF EXISTS exec_record;

CREATE TABLE exec_record (
  id                INTEGER PRIMARY KEY AUTOINCREMENT,
  user_ip           INTEGER NOT NULL,
  exec_ip           INTEGER NOT NULL,
  batch_id          INTEGER NOT NULL,
  cmd               TEXT    NOT NULL,
  exec_status       INTEGER NOT NULL,
  result            TEXT    NOT NULL,
  exec_time         INTEGER NOT NULL,
  creation_date     DATE    NOT NULL,
  last_changed_date DATE    NOT NULL
);

CREATE INDEX idx_last_changed_date
  ON exec_record (last_changed_date);

DROP TABLE IF EXISTS user;
-- user table
CREATE TABLE user (
  id        INTEGER PRIMARY KEY   AUTOINCREMENT,
  username  VARCHAR(128) NOT NULL,
  password  TEXT(128)    NOT NULL,
  status    INTEGER      NOT NULL DEFAULT 0,
  is_delete INTEGER      NOT NULL DEFAULT 0
);

INSERT INTO user(username, password) VALUES ('admin', '6f60213e401357bf1890f759bdba1b3c649b146d');