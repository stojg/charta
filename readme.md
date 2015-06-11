# Charta

## Create new development database

	mysql -u root -h 127.0.0.1
	CREATE DATABASE charta;
	CREATE USER 'charta'@'127.0.0.1' IDENTIFIED BY 'secret';
	GRANT ALL PRIVILEGES ON charta . * TO 'charta'@'127.0.0.1';

	chmod -R 777 storage/

## Todo

- Add document title to html title
- Nice URLs
- Use react JS for ajax loading

