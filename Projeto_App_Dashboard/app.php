<?php

// Definindi uma classe dashborad
// A ideia é instaciar o objeto que contenha todos os 
// atributos necessário para popular os dados do nosso dashboard
class Dashboard {
	public $data_inicio;
	public $data_fim;
	public $numeroVendas;
	public $totalVendas;

	public $clientesAtivos;
	public $clientesInativos;

	public $totalRecamacoes;
	public $totalElogios;
	public $totalSugestoes;

	public $totalDespesas;

	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
		return $this;
	}
}

class Conexao {
	private $host = 'localhost';
	private $dbname = 'dashboard';
	private $user = 'root';
	private $pass = '';

	public function conectar(){
		try{
			$conexao = new PDO(
				"mysql:host=$this->host;dbname=$this->dbname",
				"$this->user",
				"$this->pass"
			);

			// Trabalhando com os caracteres UTF-8
			$conexao->exec('set charset set utf8');

			return $conexao;

		} catch (PDOException $e) {
			echo '<p>' . $e->getMessege() . '<p>';
		}
	}
}

class Bd {
	private $conexao;
	private $dashboard;

	public function __construct(Conexao $conexao, Dashboard $dashboard){
		$this->conexao = $conexao->conectar();
		$this->dashboard = $dashboard;
	}

	public function getNumeroVendas() {
		$query = '
			SELECT COUNT(*) as numero_vendas
			FROM tb_vendas 
			WHERE data_venda BETWEEN :data_inicio AND :data_fim
		';


		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
		$stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
		$stmt->execute();

		return $stmt->fetch(PDO::FETCH_OBJ)->numero_vendas;
	}

		public function getTotalVendas() {
		$query = '
			SELECT SUM(total) as total_vendas
			FROM tb_vendas
			WHERE data_venda BETWEEN :data_inicio AND :data_fim
		';

		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
		$stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
		$stmt->execute();

		return $stmt->fetch(PDO::FETCH_OBJ)->total_vendas;
	}

	public function getClientesAtivos() {
		$query = '
			SELECT COUNT(*) as clientes_ativos
			FROM tb_clientes
			WHERE cliente_ativo = 1
		';

		$stmt = $this->conexao->prepare($query);
		$stmt->execute();

		return $stmt->fetch(PDO::FETCH_OBJ)->clientes_ativos;
	}

	public function getClientesInativos() {
		$query = '
			SELECT COUNT(*) as clientes_inativos
			FROM tb_clientes
			WHERE cliente_ativo = 0
		';

		$stmt = $this->conexao->prepare($query);
		$stmt->execute();

		return $stmt->fetch(PDO::FETCH_OBJ)->clientes_inativos;
	}

	public function getTotalReclamacoes() {
		$query = '
			SELECT COUNT(reclamacao) as total_reclamacao
			FROM tb_reclamacoes
			WHERE data_reclamacoes BETWEEN :data_inicio AND :data_fim
		';

		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
		$stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
		$stmt->execute();

		return $stmt->fetch(PDO::FETCH_OBJ)->total_reclamacao;
	}

	public function getTotalElogios() {
		$query = '
			SELECT COUNT(elogios) as total_elogios
			FROM tb_elogios
			WHERE data_elogios BETWEEN :data_inicio AND :data_fim
		';

		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
		$stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
		$stmt->execute();

		return $stmt->fetch(PDO::FETCH_OBJ)->total_elogios;
	}

	public function getTotalSugestoes() {
		$query = '
			SELECT COUNT(sugestoes) as total_sugestoes
			FROM tb_sugestoes
			WHERE data_sugestoes BETWEEN :data_inicio AND :data_fim
		';

		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
		$stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
		$stmt->execute();

		return $stmt->fetch(PDO::FETCH_OBJ)->total_sugestoes;
	}

	public function getTotalDespesas() {
		$query = '
			SELECT SUM(total) as total_despesas
			FROM tb_despesas
			WHERE data_despesa BETWEEN :data_inicio AND :data_fim
		';

		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
		$stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
		$stmt->execute();

		return $stmt->fetch(PDO::FETCH_OBJ)->total_despesas;
	}
}

// Lógica do script
// Instanciando o objeto Dashboard
$dashboard = new Dashboard();

// Instanciando o objeto Conexao (conexao com a base de dados)
$conexao = new Conexao();

// Estamos usando o explode para separar o ano do mês
//$competencia = explode('-', $_GET['competencia'])
$competencia = explode('-', $_GET['competencia']);
$ano = $competencia[0];
$mes = $competencia[1];

// cal_days_in_month é uma função nativa do php
//cal_days_in_month(calendar, month, year) - retorna o último dia do mes
$dias_do_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

// Setando alguns parametros para o objeto do dashboard
$dashboard->__set('data_inicio', $ano.'-'.$mes.'-01');
$dashboard->__set('data_fim', $ano.'-'.$mes.'-'.$dias_do_mes);


$bd = new Bd($conexao, $dashboard);


$dashboard->__set('numeroVendas', $bd->getNumeroVendas());
$dashboard->__set('totalVendas', $bd->getTotalVendas());
$dashboard->__set('clientesAtivos', $bd->getClientesAtivos());
$dashboard->__set('clientesInativos', $bd->getClientesInativos());

$dashboard->__set('totalRecamacoes', $bd->getTotalReclamacoes());
$dashboard->__set('totalElogios', $bd->getTotalElogios());
$dashboard->__set('totalSugestoes', $bd->getTotalSugestoes());

$dashboard->__set('totalDespesas', $bd->getTotalDespesas());
// Debugando
// print_r($dashboard);
// print_r($_GET['competencia']);
// print_r($ano.'-'.$mes.'-'.$dias_do_mes);

// json_encode é uma função nativa do php que transcreve o objeto para uma string json
echo json_encode($dashboard)

?>