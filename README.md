# api-framework

> This project is a framework for REST APIs developed for study.

[![PHP Version][php-image]][php-url]

The api-framework is 100% developed with vanilla php.

## Features
- DI
- Easy Controllers
- Easy Models
- Easy Routes
- Migrations
- Factories

## Installation

```sh
composer install
```

## Usage example

### Routes
To use this framework, you can create your routes in `src\routes\api.php`.
You need a controller in the `App\Controllers` namespace and a method in the controller.

Patterns:
- The route parameter is `:param`.
- The action is `Controller@method`.

Example:

```php

use Core\Routes\Router;
use App\Controllers\UserController;

# Simple routes
Router::post('/user', 'UserController@store');
Router::get('/user/:id', 'UserController@show');
Router::put('/user/:id', 'UserController@update');
Router::delete('/user/:id', 'UserController@delete');

# Controller group
Router::controller(UserController::class, function () {
    Router::get('/users', 'index');
    Router::post('/user', 'store');
    Router::get('/user/:id', 'show');
    Router::put('/user/:id', 'update');
    Router::delete('/user/:id', 'delete');
});

# Group Routes (you can pass prefix and controller)
Router::group([
    'controller' => UserController::class,
    'prefix' => '/api',
], function () {
    Router::post('/user', 'store');
    Router::get('/user/:id', 'show');
    Router::put('/user/:id', 'update');
    Router::delete('/user/:id', 'delete');
});
```

### Controllers

In the controller, you can use DI

```php
public function show(FormRequest $request, int $id)
{
    $request->all();
}
```

### Migrations

To create a migration, run:

```sh
composer make migration table_name
```

and you will have something like this:

```php
<?php

use Core\Database\Migrations\Migration;
use Core\Database\Migrations\Schema;

Migration::create('table_name', function (Schema $schema) {
    $schema->increments('id');
    $schema->string('created_at');
    $schema->string('updated_at');
});
````
now, to run the migration, run:

```sh
composer migrate
```

### Models

To have access to the database, it is possible through the model. To create a model, just create a class that extends `Core\Models\Model` if you don't define the table name, it will be defined as the class name in lower case with suffix.

Example:

```php
namespace App\Models;

use Core\Models\Model;

class User extends Model
{
    
}
```

In this case, the name of table is `users`.

### Factories

To create a factory, run:

```sh
composer make factory ModelName
```

and you will have something like this:

```php
<?php

namespace Factories;

use App\Models\ModelName;
use Core\Database\Factories\Factory;

class ModelNameFactory extends Factory
{
    protected string $class = ModelName::class;

    public function definition(): array
    {
        return [];
    }
}
```

to use the factory, you can use the `create` method:

```php
$factory = ModelNameFactory();
$factory->create();
```

## Development setup

This project is vanilla php, to setup is simple.

```sh
composer install
```

Configure the database enviroment vars in `.env` following `.env.example`.

Run application:

```sh
composer dev
```

The default port is :8080

## Meta

Carlos Junior – [@cjuniordev](https://twitter.com/cjuniordev)

[https://github.com/cjuniordev/api-framework](https://github.com/cjuniordev/api-framework)

## Contributing

1. Fork it (<https://github.com/cjuniordev/api-framework/fork>)
2. Create your feature branch (`git checkout -b feature/fooBar`)
3. Commit your changes (`git commit -am 'Add some fooBar'`)
4. Push to the branch (`git push origin feature/fooBar`)
5. Create a new Pull Request

<!-- Markdown link & img dfn's -->
[php-image]:https://img.shields.io/badge/php-%5E8.1-blue
[php-url]: https://www.php.net/
