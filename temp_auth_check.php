<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;

$credentials = ['email' => 'admin@booking.com', 'password' => 'Admin@123'];
$result = Auth::attempt($credentials);
var_dump($result);
if ($result) {
    var_dump(Auth::user()->email, Auth::user()->role);
}
