<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap {

	protected function initRoutes() {

		$routes['home'] = array(
			'route' => '/',
			'controller' => 'indexController',
			'action' => 'index'
		);

		$routes['registar'] = array(
			'route' => '/registrar',
			'controller' => 'indexController',
			'action' => 'registrar_empresario'
		);

		$routes['excluir'] = array(
			'route' => '/excluir',
			'controller' => 'indexController',
			'action' => 'excluir_empresario'
		);

		$routes['rede'] = array(
			'route' => '/rede',
			'controller' => 'indexController',
			'action' => 'visualizar_rede'
		);

		$this->setRoutes($routes);
	}

}

?>