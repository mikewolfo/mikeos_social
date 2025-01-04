CREATE TABLE user (
    user_id INTEGER PRIMARY KEY AUTOINCREMENT,
    handle TEXT NOT NULL UNIQUE,
    username TEXT NOT NULL,
    password TEXT NOT NULL,
    last_login_ip TEXT NOT NULL,
    last_login_time TIMESTAMP NOT NULL,
    pfp TEXT NOT NULL,
    banner TEXT NOT NULL,
    bio TEXT NOT NULL
);

CREATE TABLE post (
    post_id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    posted_from_ip TEXT NOT NULL,
    post_time TIMESTAMP NOT NULL,
    content TEXT NOT NULL,
    age_restricted INTEGER NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(user_id)
);

CREATE TABLE token (
    token TEXT NOT NULL,
    user_id INTEGER,
    PRIMARY KEY (token),
    FOREIGN KEY (user_id) REFERENCES user(user_id)
);

CREATE TABLE cookies (
    cookie_id TEXT NOT NULL,
    user_id INTEGER NOT NULL,
    in_use INTEGER NOT NULL, 
    creation_ip TEXT NOT NULL,
    last_used_from_ip TEXT NOT NULL,
    creation_time TIMESTAMP NOT NULL,
    last_used_time TIMESTAMP NOT NULL,
    expiration_time TIMESTAMP NOT NULL,
    PRIMARY KEY (cookie_id), 
    FOREIGN KEY (user_id) REFERENCES user(user_id)
);
