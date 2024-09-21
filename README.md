# Inventory System - Symfony 7 + Docker

## Description

This is a Symfony 7 project using PHP 8.2, MySQL 8.0, and Nginx. 
The project includes features such as importing data from an API, uploading images through Cloudinary, and using a MySQL database.

This README.md file explains how to set up the project manually on your local machine and includes the Postman collection file Symfony.postman_collection.json for easy API testing.

## Key Components
- 
- **PHP 8.2 (FPM)**: To run the Symfony application.
- **Nginx**: Web server.
- **MySQL 8.0**: Database.
- **Cloudinary**: Image storage.

## System Requirements
- PHP version >= 8.2
- Docker and Docker Compose.
- A machine with Docker installed.

## Project Structure
````
src/
│
├── Controller/
│   └── ItemController.php
│
├── Entity/
│   └── Item.php
│
├── Repository/
│   └── ItemRepository.php
│
├── Service/
│   └── WolfService.php
│   └── Update/
│       ├── AppleAirPodsUpdateService.php
│       ├── AppleIPadAirUpdateService.php
│       ├── CommonItemUpdateService.php
│       └── SamsungGalaxyS23UpdateService.php
│
├── Factory/
│   └── ItemUpdateFactory.php
|
├── Interface/
│   └── ItemUpdateInterface.php
│
└── Command/
└── ImportItemsFromAPICommand.php
````

## Setup and Running

### 1. Clone the Repository

Clone the project to your local machine:

- git clone <repository-url>
- cd <repository-directory>
- docker-compose up --build
- After docker build successfully, Please following by step
```bash
1/ docker exec -it symfony_php bash
2/ php bin/console doctrine:migrations:migrate
3/ php bin/console app:import-items-from-api
```
````
You can upload images and update quality for products by sending a POST request

Example using Postman:

URL: http://localhost:8080/api/upload-image
Method: POST
Body:
item_id: ID of the product.
image: Image file to upload.

URL: http://localhost:8080/api/update-quality
Method: POST

````
#To run sample Unit Test:
````
docker-compose exec php php bin/phpunit
````
## To build manual
## System Requirements
- Install PHP version >= 8.2
- Install Symfony CLi
- Install MySQL
- OR Xampp on Windows
````
cd you_path_project
composer install
create database with the name in .env file
run php bin/console doctrine:migrations:migrate
run php bin/console app:import-items-from-api
symfony server:start
