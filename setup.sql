CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(255)
);

CREATE TABLE config (
    id INT AUTO_INCREMENT PRIMARY KEY,
    width FLOAT,
    height FLOAT
);

CREATE TABLE links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    token VARCHAR(50)
);

INSERT INTO admin (username,password) VALUES ('admin','admin123');
