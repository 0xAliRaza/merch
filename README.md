# Merch (E-commerce Application)

Merch is an e-commerce application built with Laravel, Vue3 and Inertia. It is designed to provide an easy and efficient way to establish your Merchandise online. The application is being developed by [Ali Raza](https://0xali.com) as a personal project.

## Getting Started

To get started with this project, please follow the steps below:

### Prerequisites

-   [PHP 8.0](https://php.net/downloads/)
-   [Composer](https://getcomposer.org/download/)
-   [Node.js](https://nodejs.org/en/)
-   [NPM](https://www.npmjs.com/get-npm)

### Installation

1. Clone the repository: `git clone https://github.com/0xaliraza/merch.git`
2. Navigate to the project directory: `cd merch`
3. Install PHP dependencies: `composer install`
4. Install NPM dependencies: `npm install`
5. Copy the .env.example file to .env: `cp .env.example .env`
6. Generate an application key: `php artisan key:generate`
7. Configure your database settings in the .env file
8. Migrate the database: `php artisan migrate`
9. Seed the database: `php artisan db:seed`
10. Start the development server: `php artisan serve`

### Usage

To use the application, simply navigate to the URL where you started the development server (e.g. http://localhost:8000).

## Features

-   [x] User Authentication / Authorization
-   [ ] Admin dashboard
-   [ ] Product Management (CRUD)
-   [ ] Homepage (products index)
-   [ ] Shopping Cart
-   [ ] Orders / Tracking
-   [ ] Checkout and Payment (Stripe Integration)

## Contributing

If you are interested in contributing to this project, please feel free to fork the repository and submit a pull request with your changes.

## License

This project is licensed under the [MIT License](https://github.com/0xAliRaza/merch/blob/main/LICENSE).
