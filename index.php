<?php



spl_autoload_register(function ($class) {
    $file = __DIR__ . '/src/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once($file);
    }
});

$sp = new \ServiceProvider();

// --- APPLICATION
    //Commands
$sp->register(\Application\Commands\RegisterCommand::class);
$sp->register(\Application\Commands\SignOutCommand::class);
$sp->register(\Application\Commands\SignInCommand::class);
$sp->register(\Application\Commands\ProductCommand::class);
$sp->register(\Application\Commands\RatingCommand::class);
    // Queries
$sp->register(\Application\SignedInUserQuery::class);
$sp->register(\Application\Query\ProductQuery::class);

//services
$sp->register(\Application\Services\AuthenticationService::class);

// --- INFRASTRUCTURE
$sp->register(\Infrastructure\Session::class, isSingleton: true);
$sp->register(\Infrastructure\FakeRepository::class, isSingleton: true);

$sp->register(\Application\Interfaces\Session::class, \Infrastructure\Session::class);
$sp->register(\Application\Interfaces\UserRepository::class, \Infrastructure\Repository::class);
$sp->register(\Application\Interfaces\ProductRepository::class, \Infrastructure\Repository::class);
$sp->register(\Application\Interfaces\RatingRepository::class, \Infrastructure\Repository::class);

$sp->register(\Infrastructure\Repository::class,
    function () {
        return new \Infrastructure\Repository("localhost", "root", "", "productRating");
}, isSingleton: true);

// --- PRESENTATION
    //MVC framework
    $sp->register(\Presentation\MVC\MVC::class, function () {
        return new \Presentation\MVC\MVC();
    }, isSingleton: true);


    //Controllers
    $sp->register(\Presentation\Controllers\Home::class);
    $sp->register(\Presentation\Controllers\ProductsController::class);
    $sp->register(\Presentation\Controllers\RatingController::class);
    $sp->register(\Presentation\Controllers\UserController::class);


// === handle request
$sp->resolve(\Presentation\MVC\MVC::class)->handleRequest($sp);
