<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    realpath(__DIR__.'/../')
);

$app->withFacades(true, [
    'Ushahidi\App\Facades\Features' => 'Features'
]);

$app->withEloquent();

// Configure CORS package
// The exception handler class relies on this configuration to be loaded
// in order to provide CORS headers for requests that fail before the middleware stage
$app->configure('cors');
$app->configure('language');
/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    Ushahidi\App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    Ushahidi\App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

$app->middleware([
    Ushahidi\App\Http\Middleware\AddContentLength::class,
    Ushahidi\App\Multisite\DetectSiteMiddleware::class,
    Barryvdh\Cors\HandleCors::class,
    Ushahidi\App\Http\Middleware\MaintenanceMode::class,
    Ushahidi\App\Http\Middleware\SetLocale::class,
    v5\Http\Middleware\V5GlobalScopes::class,
]);

$app->routeMiddleware([
    'auth' => Ushahidi\App\Http\Middleware\Authenticate::class,
    //'cors'   => Ushahidi\App\Http\Middleware\CorsMiddleware::class,
    // Customised scope middleware
    'scopes' => Ushahidi\App\Http\Middleware\CheckScopes::class,
    'scope'  => Ushahidi\App\Http\Middleware\CheckForAnyScope::class,
    'expiration' => Ushahidi\App\Http\Middleware\CheckDemoExpiration::class,
    'signature' => Ushahidi\App\Http\Middleware\SignatureAuth::class,
    'feature' => Ushahidi\App\Http\Middleware\CheckFeature::class,
    'invalidJSON' => Ushahidi\App\Http\Middleware\CheckForInvalidJSON::class,
    'cache.headers.ifAuth' => Ushahidi\App\Http\Middleware\SetCacheHeadersIfAuth::class
]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

$app->register(Illuminate\Redis\RedisServiceProvider::class);
$app->register(Ushahidi\App\Providers\AppServiceProvider::class);
$app->register(Ushahidi\App\Providers\AuthServiceProvider::class);
$app->register(Ushahidi\App\Providers\EventServiceProvider::class);
$app->register(Ushahidi\App\Providers\PassportServiceProvider::class);
$app->register(Barryvdh\Cors\ServiceProvider::class);
$app->register(Sentry\SentryLaravel\SentryLumenServiceProvider::class);
$app->register(v5\Providers\MorphServiceProvider::class);
$app->register(v5\Providers\EventServiceProvider::class);
$app->register(Ushahidi\Gmail\GmailServiceProvider::class);

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->router->group([
    'namespace' => 'Ushahidi\App\Http\Controllers',
], function ($router) {
    require __DIR__.'/../routes/web.php';
});
$app->router->group([
    'namespace' => 'v5\Http\Controllers',
], function ($router) {
    require __DIR__ . '/../v5/routes/web.php';
});
return $app;
