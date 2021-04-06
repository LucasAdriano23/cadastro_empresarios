<?php

namespace App\Models;

use MF\Model\Model;

class Empresario extends Model {
    private $id;
    private $nome_completo;
    private $celular;
    private $estado;
    private $cidade;
    private $pai_empresarial_id;

    public function __get($atributo){
        return $this->$atributo;
    }

    public function __set($atributo,$valor){
        $this->$atributo = $valor;
    }

    public function salvar_empresario(){

        $sql = "INSERT INTO cadastro_empresarios.tb_empresarios
        (nome_completo, celular, estado, cidade, pai_empresarial_id)
        VALUES (:nome_completo, :celular, :estado, :cidade, :pai_empresarial_id)";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':nome_completo',$this->__get('nome_completo'));
        $stmt->bindValue(':celular',$this->__get('celular'));
        $stmt->bindValue(':estado',$this->__get('estado'));
        $stmt->bindValue(':cidade',$this->__get('cidade'));
        $stmt->bindValue(':pai_empresarial_id',$this->__get('pai_empresarial_id'));

        $stmt->execute();

        return true;

    }

    function validaCelular(){
        $sql = "SELECT celular FROM cadastro_empresarios.tb_empresarios WHERE celular = :celular ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':celular',$this->__get('celular'));
        $stmt->execute();

        $row = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if(!$row){
            return true;
        }

        return false;
    }

    public function getAll($order){
		$sql = "SELECT *
        FROM tb_empresarios
        ORDER BY $order ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getEmpresarios(){

		$sql = "SELECT e.*, CONCAT(m.Nome,' / ', m.Uf) as localizacao, pe.nome_completo as pai_empresarial
        FROM tb_empresarios as e
        INNER JOIN tb_municipio as m
        ON e.cidade = m.Codigo
        LEFT JOIN tb_empresarios as pe
        ON e.pai_empresarial_id = pe.id
        ORDER BY e.data_cadastro DESC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getLocation($id_municipio){
        $sql = "SELECT CONCAT(Nome,' / ', Uf) as localizacao
        FROM tb_municipio WHERE id = :id ";

        $stmt = $this->db->prepare($sql);
		$stmt->bindValue(':id',$id_municipio);
		$stmt->execute();

		$localizacao = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $localizacao;

    }

    public function getNomeEmpresario($id){
		$sql = "SELECT nome_completo
        FROM tb_empresarios
        WHERE id = :id";

		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();

		return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function deleteEmpresario(){
        $sql = "DELETE FROM cadastro_empresarios.tb_empresarios WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id',$this->__get('id'));
        $stmt->execute();

        return true;
    }

    public function exibeFilhos($id,$identacao){
        $sql = "SELECT id, nome_completo FROM tb_empresarios
        WHERE pai_empresarial_id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id',$id);
        $stmt->execute();

        while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
            echo '<p style="text-indent:'.$identacao.'em">- '.$row['nome_completo'].'</p>';
            $this->exibeFilhos($row['id'],$identacao + 3);
        }
    }

    public function exibeEmpresario($id){
        $sql = "SELECT id, nome_completo FROM tb_empresarios
        WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id',$id);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        echo '<p>- '.$row['nome_completo'].'</p>';
    }

}
?>