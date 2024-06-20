CREATE DATABASE test;
USE test;

-- Права
CREATE TABLE rights (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

-- Группы
CREATE TABLE groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

-- Пользователи
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL
);

-- Связи группы и права
CREATE TABLE group_rights (
    group_id INT,
    right_id INT,
    FOREIGN KEY (group_id) REFERENCES groups(id),
    FOREIGN KEY (right_id) REFERENCES rights(id)
);

-- Связи пользователей и группы
CREATE TABLE user_groups (
    user_id INT,
    group_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (group_id) REFERENCES groups(id)
);

-- Временно заблокированные права
CREATE TABLE temporary_blocked_rights (
    user_id INT,
    right_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (right_id) REFERENCES rights(id)
);


-- Тестовые данные:

INSERT INTO rights (name) VALUES ('send_messages'), ('service_api'), ('debug');
INSERT INTO groups (name) VALUES ('admin'), ('moderator'), ('user');
INSERT INTO users (username) VALUES ('user1'), ('user2'), ('user3');
