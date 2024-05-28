### Project with micro-framework

***Micro-framework contains:***

- HTTP Request handlers
- Routing based on middlewares
- Middlewares
- Twig rendering
- Console app (migration command)
- Session processing
- Event system

***Libraries that are used to create micro-framework core:***

- [DI Container](https://packagist.org/packages/league/container)
- [Fast-route](https://packagist.org/packages/nikic/fast-route)
- [Dotenv](https://packagist.org/packages/symfony/dotenv)
- [Twig](https://packagist.org/packages/twig/twig)

### Simple Library website built on this framework 

The application was created based on two architecture approaches:

- CA with CQRS principle
- Onion architecture

As for database layers, was used PostgreSQL with [Doctrine ORM and DBAL](https://www.doctrine-project.org/)

***Project contains next functionality:***
- Adding books via form input and CSV.
- The ability to view added books that were added by form input(with image)<br>and 
by CSV (books that were added by CSV do not have picture after uploading CSV)
- In addition to the previous point, in table with books that do not have picture,<br>
there is functionality to upload image for each book.
- Deleting books
- Some frontend
- User authentication
- Opportunity to see each book
- Pagination

As storage for book preview images was used [Cloudinary cloud service](https://cloudinary.com/)

***Project requirements:***

.env file with variables: 
- **Database**
  - DB_PASS
  - DB_HOST
  - DB_USER
  - DB_NAME
  - DB_DRIVER (pdo_pgsql)
- **Application**
  - APP_ENV (if local, dev, test will output errors, if else - just 500 Server Error)
- **Cloudinary** 
  - CLOUD_NAME
  - CLOUD_API
  - API_SECRET

If you want to use **console app**:
In config/app.php change your path and namespace for controllers and migration folder

### To run app:

In root directory:
````
composer install
````
````
docker-compose up -d --build
````

***Console app***

Available only migration and generation commands:

To generate migration file:
````
php micronsole migration generate
````
To start migration:
````
php micronsole migration migrate
````
To generate Controller file
````
php micronsole controller generate --name="HomeController"
````
