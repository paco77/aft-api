<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

Auth::login(App\Models\User::where('role', 'coach')->first());
$errors = new \Illuminate\Support\ViewErrorBag();
$errors->put('default', new \Illuminate\Support\MessageBag());
View::share('errors', $errors);
$html = view('admin.users.create', ['coaches' => App\Models\User::where('role', 'coach')->get()])->render();
file_put_contents('test_view_coach.html', $html);
echo "Rendered for coach!\n";
