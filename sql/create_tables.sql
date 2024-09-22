
CREATE TABLE user (
	user_id int NOT NULL AUTO_INCREMENT,
    handle varchar(100) NOT NULL UNIQUE,
    username varchar(100) NOT NULL,
    password varchar(256) NOT NULL,
    last_login_ip varchar(15) NOT NULL,
    last_login_time TIMESTAMP NOT NULL,
    pfp varchar(256) NOT NULL,
    banner varchar(256) NOT NULL,
    bio varchar(512) NOT NULL,
    PRIMARY KEY (user_id)
);



CREATE TABLE post (
	post_id int NOT NULL AUTO_INCREMENT,
	user_id int NOT NULL,
    posted_from_ip char(15) NOT NULL,
	post_time TIMESTAMP NOT NULL,
    content varchar(511) NOT NULL,
    ageRestricted BOOLEAN NOT NULL,
    PRIMARY KEY (post_id),
    FOREIGN KEY (user_id) REFERENCES user(user_id)
);

CREATE TABLE token (
    token varchar(511) NOT NULL,
    user_id int,
    PRIMARY KEY (token),
    FOREIGN KEY (user_id) REFERENCES user(user_id)
);

commit;