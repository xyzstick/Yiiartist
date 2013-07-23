/**
* Database schema required by CDbAuthManager.
*/
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

DROP TABLE if EXISTS tbl_User;

CREATE TABLE tbl_User (
	id INTEGER NOT NULL AUTO_INCREMENT,
	username VARCHAR(128) NOT NULL,
	email VARCHAR(128) NOT NULL,
	password VARCHAR(128) NOT NULL,
	ipaddress CHAR(40) NOT NULL default '',
	status TINYINT(1) NOT NULL DEFAULT '0',
	profile TEXT,
	created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	last_login TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	lock_account TINYINT(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (id),
	UNIQUE KEY username (username),
	UNIQUE KEY email (email),
	KEY status (status)
) ENGINE=InnoDB;

/* Both passwords are "demo" */
INSERT INTO tbl_User (username, password, email, status, last_login) VALUES ('admin', '$2a$10$JTJf6/XqC94rrOtzuF397OHa4mbmZrVTBOQCmYD9U.obZRUut4BoC', 'admin@localhost.com', 1, '2013-07-20 07:45:23');
INSERT INTO tbl_User (username, password, email) VALUES ('demo','$2a$10$JTJf6/XqC94rrOtzuF397OHa4mbmZrVTBOQCmYD9U.obZRUut4BoC','demo@localhost.com');