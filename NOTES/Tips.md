Tips:
when you want to update a record
you can use `update` method

```php
$product->update(['quantity' => 'quantity - 1']); // this will not work
// UPDATE `products` SET `quantity` = 'quantity - 1' WHERE `products`.`id` = 1
```

> laravel will inject the value as a string, not as a raw query.

```php
$product->update(['quantity' => DB::raw('quantity - 1')]); // this will work
// UPDATE `products` SET `quantity` = quantity - 1 WHERE `products`.`id` = 1
// equal to
// UPDATE `products` SET `quantity` = products(quantity) - 1 WHERE `products`.`id` = 1
```

when you want use Class as type in parameter, you should register this class in `AppServiceProvider` in `boot` method

so when laravel do Class Reflection and know that this class is used as type in parameter, it will resolve it from the container.

```php
public function boot()
{
    $this->app->bind('App\Classes\MyClass', function ($app) {
        return new MyClass($app->make('App\Classes\AnotherClass'));
    });
}
```

or you can use `singleton` method

```php
public function boot()
{
    $this->app->singleton('App\Classes\MyClass', function ($app) {
        return new MyClass($app->make('App\Classes\AnotherClass'));
    });
}
```

or you you pass this parameter in the constructor of the class

```php
public function __construct(MyClass $myClass)
{
    $this->myClass = $myClass;
}
```
