 DROP DATABASE IF EXISTS blog_p4oc;
CREATE DATABASE blog_p4oc CHARACTER SET 'utf8';

USE blog_p4oc;

CREATE TABLE Articles
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
    admin         tinyint(1)
)
    ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE Comments
(
    id            MEDIUMINT             UNSIGNED      PRIMARY KEY   AUTO_INCREMENT,
    author        VARCHAR(50)     NOT NULL,
    content       TEXT            NOT NULL,
    created_date  DATETIME        NOT NULL      DEFAULT       CURRENT_TIMESTAMP,
    article_id    TINYINT         UNSIGNED      NOT NULL,
    user_id       SMALLINT        UNSIGNED      NOT NULL,
    moderated     tinyint(1)      NOT NULL,
    CONSTRAINT    fk_post_id      FOREIGN KEY   (article_id)     REFERENCES      Articles(id),
    CONSTRAINT    fk_user_id      FOREIGN KEY   (user_id)     REFERENCES      Users(id)
)
    ENGINE=INNODB DEFAULT CHARSET=utf8;