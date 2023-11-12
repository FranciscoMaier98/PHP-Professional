<?php declare(strict_types=1);
use Tracy\Debugger;

define('ROOT_DIR', dirname(__DIR__));
require ROOT_DIR . '/vendor/autoload.php';

//\Tracy\OutputDebugger::enable();
//Debugger::enable();

$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

// $controller = new \SocialNews\FrontPage\Presentation\FrontPageController();
// $response = $controller->show($request);

// if(!$response instanceof \Symfony\Component\HttpFoundation\Response) {
//     throw new \Exception(
//         'Controller methods must return a Response object'
//     );
// }

$dispatcher = \FastRoute\simpleDispatcher(
    function(\FastRoute\RouteCollector $r) {
        $routes = include (ROOT_DIR . '/src/Routes.php');
        foreach($routes as $route){
            $r->addRoute(...$route);
        }
    }
);

$routeInfo = $dispatcher->dispatch(
    $request->getMethod(),
    $request->getPathInfo()
);

switch($routeInfo[0]) {
    case \FastRoute\Dispatcher::NOT_FOUND:
        $response = new \Symfony\Component\HttpFoundation\Response(
            'Not found',
            404
        );
        break;
    case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $response = new \Symfony\Component\HttpFoundation\Response(
            'Method not allowed',
            405
        );
        break;
    case \FastRoute\Dispatcher::FOUND:
        [$controllerName, $method] = explode ('#', $routeInfo[1]);
        $vars = $routeInfo[2];

        //$factory = new SocialNews\Framework\Rendering\TwigTemplateRendererFactory();
        //$templateRenderer = $factory->create();
        //$controller = new $controllerName($templateRenderer);

        $injector = include('Dependencies.php');
        $controller = $injector->make($controllerName);
        $response = $controller->$method($request, $vars);
        break;
}

echo $response;
//$response->prepare($request);
//$response->send();

//throw new \Exception; 
echo 'Hello from the bootstrap file :)';