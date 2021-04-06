<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action {

	public function index() {

		$empresario = Container::getModel('Empresario');

		$empresarios = $empresario->getAll('nome_completo');

		$listagem_empresarios = $empresario->getEmpresarios();

		$this->view->empresarios = $empresarios;
		$this->view->listagem_empresarios = $listagem_empresarios;

		$this->render('index');
	}

	public function registrar_empresario(){

		$empresario = Container::getModel('Empresario');

		$empresario->__set('nome_completo',isset($_POST['nome_empresario']) ? $_POST['nome_empresario'] : '');
		$empresario->__set('celular',isset($_POST['celular']) ? $_POST['celular'] : '');
		$empresario->__set('estado',isset($_POST['estado']) ? $_POST['estado'] : '0');
		$empresario->__set('cidade',isset($_POST['cidade']) ? $_POST['cidade'] : '0');
		$empresario->__set('pai_empresarial_id',isset($_POST['pai_empresarial']) && !empty($_POST['pai_empresarial']) ? $_POST['pai_empresarial'] :'0');

		if($empresario->validaCelular()){
			$empresario->salvar_empresario();
			$this->view->erroCadastro = false;

			$empresarios = $empresario->getAll('nome_completo');
			$this->view->empresarios = $empresarios;

			$listagem_empresarios = $empresario->getEmpresarios();
			$this->view->listagem_empresarios = $listagem_empresarios;

			$this->render('index');
		}else{
			$this->view->erroCadastro = true;
			$this->view->nome_empresario = $_POST['nome_empresario'];
			$this->view->celular = $_POST['celular'];
			$this->view->estado = $_POST['estado'];
			$this->view->cidade = $_POST['cidade'];
			$this->view->pai_empresarial = $_POST['pai_empresarial'];

			$empresarios = $empresario->getAll('nome_completo');
			$listagem_empresarios = $empresario->getEmpresarios();
		
			$this->view->empresarios = $empresarios;
			$this->view->listagem_empresarios = $listagem_empresarios;

			$this->render('index');
		}
	}

	public function excluir_empresario(){
		$empresario = Container::getModel('Empresario');

		$empresario->__set('id',isset($_GET['id']) ? $_GET['id'] : '');

		$empresario->deleteEmpresario();

		$listagem_empresarios = $empresario->getEmpresarios();
		$empresarios = $empresario->getAll('nome_completo');
		
		$this->view->empresarios = $empresarios;
		$this->view->listagem_empresarios = $listagem_empresarios;

		$this->render('index');
	}

	public function visualizar_rede(){
		$empresario = Container::getModel('Empresario');
		echo "<div class='container'>";
		echo "<h1 class='text-center'>Rede</h1>";
		$empresario->exibeEmpresario($_GET['id']);
		$empresario->exibeFilhos($_GET['id'],3);
		echo "</div>";

		$this->render('redes','redes');
	}

}


?>