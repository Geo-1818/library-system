<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$request = Illuminate\Http\Request::create('/');
$app->instance('request', $request);
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('email', 'admin@booking.com')->first();
if ($user) {
    echo "found:\n";
    var_dump($user->getAttributes());
} else {
    echo "not found\n";
}
