<?php
// use Cartalyst\Sentinel\Native\Facades\Sentinel;
// use Cartalyst\Sentinel\Activations\EloquentActivation as Activation;
// print_r($pages->course);
// die(header('Location:/news'));
$credentials = [
    'email'    => 'test@test.com',
    'password' => 'foobar',
];
// $data=Sentinel::registerAndActivate($credentials);

// $data=Sentinel::authenticate($credentials);
// $data = Sentinel::check();
$data = $container->sentinel->check();
// dd($data);
// $user = Sentinel::findById(1);

// $activation = Activation::create($user);
// dd($activation);