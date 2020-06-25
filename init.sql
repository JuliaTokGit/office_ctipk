CREATE DATABASE IF NOT EXISTS office;
CREATE USER IF NOT EXISTS 'office'@'localhost' IDENTIFIED BY 'office';
GRANT ALL PRIVILEGES ON 'office'.* TO 'office'@'localhost';