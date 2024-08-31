
# Judson's ThriveCart Test

  

## Requirements

  

To work with this project, you need to have the following installed:

  

-  **PHP 8.2** and **Composer**

-  **Docker** and **Docker Compose**

  

## Setup Instructions

  

Follow these steps to set up and run the application:

  

1.  **Install Dependencies**

  

Ensure you have `composer` installed. Run the following command to install all required PHP dependencies:

  

```bash
composer install
```
  

2.  **Build and Start Docker Containers**

  

Use the provided Makefile to build and start Docker containers. Run:

  

```bash
make up
```

  

3.  **Access the Application**

  

Once the containers are up and running, you can access the application at:

  

```bash
http://localhost:8080/
```
  

## Additional Commands

  

- Run Tests

  
```bash
vendor/bin/phpunit
```
  

- Run PHPStan
```bash
vendor/bin/phpstan analyse
```