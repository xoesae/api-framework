# api-framework

> This project is a framework for REST APIs developed for study.

[![PHP Version][php-image]][php-url]

The api-framework is 100% developed with vanilla php.

## Features
- DI
- Easy Controllers
- Easy Models
- Easy Routes

## Installation

```sh
composer install
```


## Usage example

To use this framework, you can create your routes in `src\routes\api.php`.

Example:

```php
$this->get('user/:id', 'UserController@show');
```

In the controller, you can use DI

```php
public function show(FormRequest $request, int $id)
{
    $request->all();
}
```

To have access to the database, it is possible through the model. To create a model, just create a class that extends `Core\Models\Model` and defines its columns and table name, if you don't define the table name, it will be defined as the class name in lower case with suffix.

Example:

```php
namespace App\Models;

use Core\Models\Model;

class User extends Model
{
    public string $name = 'VARCHAR(255) NOT NULL';
    public string $email = 'VARCHAR(255) NOT NULL';
    public string $password = 'VARCHAR(255) NOT NULL';
}
```

In this case, the name of table is `users`.


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
