# Setting up and Testing the PostcodeApp

This is a guide for setting up and testing the PostcodeApp, a Symfony-based standalone PHP application that allows you to download and import UK postcodes into a database and provides API endpoints to search for postcodes with partial string matches and postcodes near a location.

## Prerequisites

Before proceeding with the setup, ensure that you have the following prerequisites installed on your system:

- PHP 7.2 or higher
- Composer (https://getcomposer.org/)
- Symfony CLI (https://symfony.com/download)

## Installation

1. Clone the repository:

```bash
git clone https://github.com/OlabodeAbesin/PostcodeApp.git
```

2. Change directory to the project root:

```bash
cd PostcodeApp
```

3. Install dependencies:

```bash
composer install
```

4. Set up the database:

Edit the `.env` file in the project root and configure your database connection. For example, if you are using MySQL:

```
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
```

5. Create the database tables:

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

6. Download and import the postcodes:

```bash
php bin/console app:import-postcodes
```

## Testing

1. Start the Symfony development server:

```bash
symfony serve
```

2. Test the API endpoints:

Partial string match:

```bash
curl http://127.0.0.1:8000/api/postcodes/partial/LN
```

Postcodes near a location (latitude, longitude):

```bash
curl http://127.0.0.1:8000/api/postcodes/nearby/51.5074/-0.1278
```

Replace `127.0.0.1:8000` with the appropriate domain or IP if you are hosting the application on a different server.

## Unit Tests

```bash
php vendor/bin/phpunit
```
If all the tests pass, you should see a success message. 

## Additional Notes

- The PostcodeApp uses Symfony's built-in development server for testing purposes. For production deployment, consider using a more robust web server (e.g., Apache or Nginx) with PHP-FPM.

- For security reasons, consider using environment variables to store sensitive information like database credentials. Symfony supports the use of environment variables in the `.env` file.

- If you encounter any issues during the setup or testing, refer to Symfony's official documentation (https://symfony.com/doc) or the relevant package documentation for troubleshooting and solutions.

- Always sanitize and validate user input to prevent security vulnerabilities like SQL injection or XSS attacks.

- Remember to secure your production environment by disabling Symfony's development mode and enabling appropriate security measures. Review Symfony's security recommendations for production deployments.

- Keep the application and its dependencies up to date by periodically running `composer update` to fetch the latest package versions.

## Conclusion

You should now have the PostcodeApp set up and running. The application allows you to download and import UK postcodes, and it provides JSON API endpoints for partial string matches and postcodes near a location.