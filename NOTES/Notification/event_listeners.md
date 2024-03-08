# Event Listeners in Laravel

> why we use `Events and Listeners` in Laravel?
> we use them to apply `Observer Pattern` in Laravel.
> we use them to apply `Separation of Concerns` Principle.
> instead of doing my responsibility in the same class, I can delegate it to another class.
> Wordpress built on `Observer Pattern` and `Separation of Concerns` Principle.
> because users cannot change the core of Wordpress, they can only change the plugins.
> plugins are just a bunch of classes that are responsible for a specific task.

## 1. Create an Event

```bash
php artisan make:event OrderShipped
```

```php
class OrderShipped
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }
}
```

## 2. Create a Listener

```bash
php artisan make:listener SendShipmentNotification
```

```php
class SendShipmentNotification
{
    public function __construct()
    {
        //
    }

    public function handle(OrderShipped $event)
    {
        // Access the order using $event->order...
    }
}
```

## 3. Register the Event and Listener

```php
// EventServiceProvider.php
protected $listen = [
    'App\Events\OrderShipped' => [
        'App\Listeners\SendShipmentNotification',
    ],
    // Or
    'order.shipped' => [
        'App\Listeners\SendShipmentNotification',
    ],
    // Or
    OrderShipped::class => [
        SendShipmentNotification::class,
    ],
];
```

## 4. Dispatch the Event

```php
// or 1#
event(new OrderShipped($order)); // Listener should accept the $event as `OrderShipped` class
// or 2#
event("order.shipped", $order, $user); // Listener should accept the $order as first parameter as `Order` model
// or 3#
OrderShipped::dispatch($order);
```

## 5. Handle the Event

```php

class SendShipmentNotification
{
    public function __construct()
    {
        //
    }

    // if you pass `event("string") not Object, you should remove the paramter from the handle method.
    public function handle(OrderShipped $event)
    {
        // Access the order using $event->order...
    }

    // if you pass `event("string") not Object
    // used like this event("order.shipped", $order, $user);
    public function handle($order, $user)
    {
        // Access the order using $event->order...
    }
}
```

## 6. Queueing Listeners

```php
class SendShipmentNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle(OrderShipped $event)
    {
        // Access the order using $event->order...
    }

    /**
     * The job failed to process.
     * @param  \Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        // Send user notification of failure, etc...
    }

    /**
     * Determine whether the listener should be queued.
     * @param  \App\Events\OrderShipped  $event
     * @return bool
     */
    public function shouldQueue(OrderShipped $event)
    {
        return $event->order->price > 100;
    }
}
```

## 7. Stopping the Propagation of an Event

```php
public function handle(OrderShipped $event)
{
    if ($event->order->price > 100) {
        return false;
        // returning false will stop the propagation of the event to other listeners.
    }
}
```

source: https://laravel.com/docs/5.8/events

you can use `EventServiceProvider` to register the events and listeners.

## 8. Event Subscribers

```php
<?php

namespace App\Listeners;

use Illuminate\Contracts\Events\Dispatcher;

class UserEventSubscriber
{
    /**
     * Handle user login events.
     */
    public function onUserLogin($event) {}

    /**
     * Handle user logout events.
     */
    public function onUserLogout($event) {}

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Login',
            'App\Listeners\UserEventSubscriber@onUserLogin'
        );

        $events->listen(
            'Illuminate\Auth\Events\Logout',
            'App\Listeners\UserEventSubscriber@onUserLogout'
        );
    }
}
```

source: https://laravel.com/docs/5.8/events#event-subscribers

Laravel provides many events out of the box, such as `Illuminate\Auth\Events\Login` and `Illuminate\Auth\Events\Logout`, which are fired when a user logs into or out of your application.
