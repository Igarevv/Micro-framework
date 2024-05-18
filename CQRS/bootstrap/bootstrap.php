<?php

use App\Providers\ProviderInterface;
use App\Providers\ServiceProvider;

$providers = [
  ServiceProvider::class
];

foreach ($providers as $provider){
    $provider = $container->get($provider);

    /** @var ProviderInterface $provider */
    $provider->register();
}