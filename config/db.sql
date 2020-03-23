 DROP DATABASE IF EXISTS blog_p4oc;
CREATE DATABASE blog_p4oc CHARACTER SET 'utf8';

USE blog_p4oc;

CREATE TABLE Posts
(
    id            TINYINT   UNSIGNED  PRIMARY KEY AUTO_INCREMENT,
    title         VARCHAR(100)   NOT NULL  UNIQUE,
    content       TEXT           NOT NULL,
    created_date  DATETIME       NOT NULL  DEFAULT       CURRENT_TIMESTAMP
)
    ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE Users
(
    id            SMALLINT      	                UNSIGNED  PRIMARY KEY AUTO_INCREMENT,
    name	      VARCHAR(50)   	                NOT NULL  UNIQUE,
    email         VARCHAR(100)  	                NOT NULL  UNIQUE,
    pass          VARCHAR(100)  	                NOT NULL,
    role          SET('admin','member')             NOT NULL
)
    ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE Comments
(
    id            INT             UNSIGNED      PRIMARY KEY   AUTO_INCREMENT,
    author        VARCHAR(50)     NOT NULL,
    content       TEXT            NOT NULL,
    created_date  DATETIME        NOT NULL      DEFAULT       CURRENT_TIMESTAMP,
    post_id       TINYINT         UNSIGNED      NOT NULL,
    user_id       SMALLINT        UNSIGNED      NOT NULL,
    moderated     tinyint(1)      NOT NULL,
    CONSTRAINT    fk_post_id      FOREIGN KEY   (post_id)     REFERENCES      Posts(id),
    CONSTRAINT    fk_user_id      FOREIGN KEY   (user_id)     REFERENCES      Users(id)
)
    ENGINE=INNODB DEFAULT CHARSET=utf8;