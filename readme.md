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

2. Test the API endpoints (This can be done via postman or in a browser):

Partial string match:

```bash
curl http://127.0.0.1:8000/api/postcodes/partial/LN
```

Postcodes near a location (latitude, longitude):

```bash
curl http://127.0.0.1:8000/api/postcodes/nearby/51.5074/-0.1278
```

Replace `127.0.0.1:8000` with the appropriate domain or IP if you are hosting the application on a different server.

## Things I'll add if I spent more time working on this
- Authenticating the api endpoints using a JWT Bearer token
- I'd spend more time on automated testing (configuring up the test environment). I wrote tests though :)
- This PostcodeApp uses Symfony's built-in development server for testing purposes. For production deployment, I'll be using a more robust web server (e.g., Apache or Nginx) with PHP-FPM.
- The url parameters in the second endpoint (Longitude and Latitude) are only validated as a data type and currently not throwing nice errors. I'd sanitize and validate user input for better UX and to prevent security vulnerabilities.
- If the test question didn't say using a controller action. I'd also consider moving my business logic out of the controller into a service class. 
- Keep the application and its dependencies up to date by periodically running `composer update` to fetch the latest package versions.

## Conclusion

You should now have the PostcodeApp set up and running. The application allows you to download and import UK postcodes, and it provides JSON API endpoints for partial string matches and postcodes near a location.