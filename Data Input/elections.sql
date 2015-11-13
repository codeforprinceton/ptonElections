SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Create Database CREATE DATABASE IF NOT EXISTS ptonElections
-- ----------------------------

-- ----------------------------
--  Table structure for districts
-- ----------------------------
DROP TABLE IF EXISTS districts;
CREATE TABLE districts (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for election_district_maps
-- ----------------------------
DROP TABLE IF EXISTS election_district_maps;
CREATE TABLE election_district_maps (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  election_district_id bigint(20) NOT NULL,
  latitude decimal(20,8) NOT NULL,
  longitude decimal(20,8) NOT NULL,
  plot_order bigint(20) NOT NULL,
  plot_type varchar(255) NOT NULL,
  PRIMARY KEY (id),
  KEY election_district_maps_election_districts_fk (election_district_id),
  CONSTRAINT election_district_maps_election_districts_fk FOREIGN KEY (election_district_id) REFERENCES election_districts (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for election_districts
-- ----------------------------
DROP TABLE IF EXISTS election_districts;
CREATE TABLE election_districts (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  election_id bigint(20) NOT NULL,
  district_id bigint(20) NOT NULL,
  machine_count tinyint(4) NOT NULL,
  reg_voters bigint(20) NOT NULL,
  PRIMARY KEY (id),
  KEY election_districts_elections_fk (election_id),
  KEY election_districts_districts_fk (district_id),
  CONSTRAINT election_districts_districts_fk FOREIGN KEY (district_id) REFERENCES districts (id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT election_districts_elections_fk FOREIGN KEY (election_id) REFERENCES elections (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for elections
-- ----------------------------
DROP TABLE IF EXISTS elections;
CREATE TABLE elections (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  election_date datetime NOT NULL,
  location varchar(255) NOT NULL,
  name varchar(255) NOT NULL,
  active boolean NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for questions
-- ----------------------------
DROP TABLE IF EXISTS questions;
CREATE TABLE questions (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  election_id bigint(20) NOT NULL,
  question varchar(255) NOT NULL,
  question_order tinyint(4) NOT NULL,
  PRIMARY KEY (id),
  KEY questions_elections_fk (election_id),
  CONSTRAINT questions_elections_fk FOREIGN KEY (election_id) REFERENCES elections (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for responses
-- ----------------------------
DROP TABLE IF EXISTS responses;
CREATE TABLE responses (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  question_id bigint(20) NOT NULL,
  response varchar(255) NOT NULL,
  response_order tinyint(4) NOT NULL,
  PRIMARY KEY (id),
  KEY responses_questions_fk (question_id),
  CONSTRAINT responses_questions_fk FOREIGN KEY (question_id) REFERENCES questions (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for results
-- ----------------------------
DROP TABLE IF EXISTS results;
CREATE TABLE results (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  election_district_id bigint(20) NOT NULL,
  response_id bigint(20) NOT NULL,
  machine_number tinyint(4) NOT NULL,
  tally bigint(20) NOT NULL,
  user_id bigint(20) NOT NULL,
  time_changed datetime NOT NULL,
  PRIMARY KEY (id),
  KEY results_election_districts_fk (election_district_id),
  KEY results_responses_fk (response_id),
  KEY results_users_fk(user_id),

  CONSTRAINT results_users_fk FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT results_election_districts_fk FOREIGN KEY (election_district_id) REFERENCES election_districts (id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT results_responses_fk FOREIGN KEY (response_id) REFERENCES responses (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  first varchar(255) NOT NULL,
  last varchar(255) NOT NULL,
  username varchar(255) NOT NULL,
  password varchar(255) NOT NULL,
  privilege varchar(255) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;
