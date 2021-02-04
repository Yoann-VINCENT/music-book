# Music book
Make your own music book app !

## Getting Started

### Prerequisites

1. Check composer is installed
2. Check yarn & node are installed

### Install

1. Clone this project
2. Create an `.env.local` file
3. In the newly created .env.local file, define the following variables:
    * `DATABASE_URL`
    * `MAILER_DSN`
4. Run `composer install`
5. Run `php bin/console d:d:c` to create the database
6. Run `php bin/console d:m:m` to make the migration
7. Run `php bin/console d:f:l` to load the fixtures
8. Run `yarn install`
9. Run `yarn encore dev` to build assets

### Working

1. Run `symfony server:start` to launch your local php web server
2. Run `yarn run dev --watch` to launch your local server for assets
