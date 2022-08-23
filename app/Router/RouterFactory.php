<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Routing\Route;



final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList;


		$router->addRoute('render/<image>', 'Render:ViewImage');
		$router->addRoute('login', 'Login:login');
		$router->addRoute('loginjwt', 'Login:loginJwt');
		$router->addRoute('register', 'Login:register');
		$router->addRoute('activation/<email>/<activation_code>', 'Login:activation');
		$router->addRoute('refresh', 'Login:refresh');
		$router->addRoute('protected', 'Login:protected');
		$router->addRoute('renew', 'Login:renew');
		$router->addRoute('cookie', 'Login:handleCookie');
		$router->addRoute('logout', 'Login:logOut');
		$router->addRoute('photo/<image=1>', 'Photo:photo');
		$router->addRoute('view/<image>', 'Photo:view');
		$router->addRoute('album/<album=1>', 'Album:album');
		$router->addRoute('share', 'Album:share');
		$router->addRoute('<presenter>/<action>[/<id>]', 'Homepage:default');

		return $router;
	}
}
