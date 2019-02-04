<?
namespace Inovuerj\ADO;
class mysql {
	
	/*
	Fun��es P�blicas
	
	instrucao($instrucao);
	Seta a instru��o a ser executada.
	
	resultado($limite = "", $inicio = "");
	Retorna um array com o resultado da instru��o previamente setada com limite e in�cio num�ricos opcionais.
	
	num_resultados();
	Retorna o n�mero de resultados da instru��o previamente setada.
	
	mostra_instrucao();
	Retorna a instru��o setada.
	
	executa();
	Executa a instru��o setada.
	*/
	
	private $instrucao;
	
	public function instrucao($instrucao) {
		$this->instrucao = $instrucao;
	}
	
	public function resultado($limite = "", $inicio = "") {	
	global $conexao, $msg_erro_query, $msg_sem_registros;
		
		if(is_numeric($limite) && is_numeric($inicio)) {
			$sql_limite = " LIMIT " . $inicio . "," . $limite;
			$query = mysql_query($this->instrucao . $sql_limite, $conexao) or die($msg_erro_query . " (" . mysql_error($conexao) . ")");
		} elseif(is_numeric($limite)) {
			$sql_limite = " LIMIT " . $limite;
			$query = mysql_query($this->instrucao . $sql_limite, $conexao) or die($msg_erro_query . " (" . mysql_error($conexao) . ")");
		} else {
			$query = mysql_query($this->instrucao, $conexao) or die($msg_erro_query . " (" . mysql_error($conexao) . ")");
		}
		
		$resultado = array();
		
		while($row = mysql_fetch_assoc($query)) {
			array_push($resultado, $row);
		}
		
		mysql_free_result($query);
		
		if(count($resultado) == 0) $resultado = $msg_sem_registros;
		
		return $resultado;
	}
	
	public function num_resultados() {	
	global $conexao, $msg_erro_query;	
		$query = mysql_query($this->instrucao, $conexao) or die($msg_erro_query . " (" . mysql_error($conexao) . ")");
		$num_resultados = mysql_num_rows($query);
		mysql_free_result($query);
		return $num_resultados;
	}
	
	public function mostra_instrucao() {
		return $this->instrucao;
	}
	
	public function executa() {		
	global $conexao, $msg_erro_query;
		$query = mysql_query($this->instrucao, $conexao) or die($msg_erro_query . " (" . mysql_error($conexao) . ")");	
	}
	
}
?>