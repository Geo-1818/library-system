<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$request = Illuminate\Http\Request::create('/login', 'POST', ['email' => 'admin@booking.com', 'password' => 'Admin@123']);
$app->instance('request', $request);
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$request->setLaravelSession($app->make('session.store'));

$guard = Illuminate\Support\Facades\Auth::guard();
$guard->setRequest($request);

var_dump($guard->attempt(['email' => 'admin@booking.com', 'password' => 'Admin@123']));
if ($guard->check()) {
    echo "user: " . $guard->user()->email . "\n";
}
