<?php 
	class Vida_model extends CI_Model{
		
		function __construct(){
			$this->load->database();
		}

		public function validarCodigo($codigo){
			$c = $this->db->select('codigo')->
					where(array('codigo'=>$codigo))->
					get('aplicacion')->
					row();
			return $c;
		}

		public function verEstado($codigo){
			$e = $this->db->select('estado')->
					where(array('codigo'=>$codigo))->
					get('aplicacion')->
					result_array();
			$estado = $e[0]['estado'];
			return $estado;
		}
		
		public function obtenerDatos($codigo){
			$d = $this->db->select('encuestado.nombre, aplicacion.codigo')->
					where(array('encuestado.idEncuestado = aplicacion.idEncuestado AND aplicacion.codigo =' => $codigo))->
					get('aplicacion, encuestado')->
					row();
			return $d;
		}

		public function verLimite($codigo){
			$l = $this->db->select_max('reactivo.indice')->
					where(array('aplicacion.idEncuesta = encuesta.idEncuesta AND
							reactivo.idEncuesta = encuesta.idEncuesta AND
							aplicacion.codigo =' => $codigo))->
					get('aplicacion, encuesta, reactivo')->
					result_array();
			$indice = $l[0]['indice'];
			return $indice;
		}

		public function verPregunta($codigo){
			$p = $this->db->select('aplicacion.pregunta')->
					where(array('aplicacion.idEncuesta = encuesta.idEncuesta AND
							aplicacion.codigo =' => $codigo))->
					get('aplicacion, encuesta')->
					result_array();
			$pregunta = $p[0]['pregunta'];
			return $pregunta;
		}

		public function verIdAplicacion($codigo){
			$p = $this->db->select('idAplicacion')->
					where(array('codigo =' => $codigo))->
					get('aplicacion')->
					result_array();
			$idAplicacion = $p[0]['idAplicacion'];
			return $idAplicacion;
		}

		public function verIdEncuesta($codigo){
			$p = $this->db->select('idEncuesta')->
					where(array('codigo =' => $codigo))->
					get('aplicacion')->
					result_array();
			$idEncuesta = $p[0]['idEncuesta'];
			return $idEncuesta;
		}
		
		public function verNombreEncuesta($idEncuesta){
			$p = $this->db->select('encuesta.nombre')->
					where('aplicacion.idEncuesta = encuesta.idEncuesta AND aplicacion.idEncuesta = '.$idEncuesta)->
					get('aplicacion,encuesta')->
					result_array();
			$nombreEncuesta = $p[0]['nombre'];
			return $nombreEncuesta;
		}
		public function verDatos($codigo){
			$q = $this->db->select('reactivo.idReactivo, reactivo.reactivo,reactivo.comentario, reactivo.indice, aplicacion.codigo')->
					where(array( 'reactivo.indice = aplicacion.pregunta AND aplicacion.codigo =' => $codigo))->
					get('reactivo,aplicacion')->
					row();
			return $q;
		}

		public function verDatosBack($codigo,$pregunta){
			$q = $this->db->select('reactivo.idReactivo, reactivo.reactivo,
					reactivo.comentario, reactivo.indice,aplicacion_vida.valor ,aplicacion.codigo')->
					where(array('aplicacion_vida.idAplicacion = aplicacion.idAplicacion AND 
					aplicacion_vida.idReactivo = reactivo.idReactivo AND
					aplicacion.codigo =' => $codigo, 'reactivo.indice =' => $pregunta))->
					get('reactivo,aplicacion,aplicacion_vida')->
					row();
			return $q;
		}
		
		public function insertarAplicacionVida($idReactivo,$idAplicacion,$valor){
			$this->db->query("INSERT INTO aplicacion_vida VALUES($idReactivo,$idAplicacion,$valor)
					ON DUPLICATE KEY UPDATE valor = $valor;");
		}

		public function ultimaRegistrada($pregunta,$idAplicacion){
			$this->db->query("UPDATE aplicacion SET pregunta = $pregunta WHERE idAplicacion = $idAplicacion;");
		}

		public function busca_menor_mayor($idEncuesta){
			$this->db->select('indice');
			$this->db->from('reactivo');
			$this->db->order_by('indice','asc');
			$this->db->where('idEncuesta',$idEncuesta);
			$query=$this->db->get();
			$row=$query->result_array();
			$first = $row[0];
			$last = $row[count($row)-1];
			return [$first,$last];
		}

		public function estadoFecha($idAplicacion){
			$data = array(
				'fechaConclusion' => date('Y-m-d'),
				'estado' => 'Finalizado'
			 );
			$this->db->where('idAplicacion', $idAplicacion);
			$this->db->update('aplicacion', $data);	
		}

		public function obtenerCluster($a,$b,$idAplicacion){
			$p = $this->db->select_sum('aplicacion_vida.valor')->
					where('reactivo.idReactivo = aplicacion_vida.idReactivo AND
						aplicacion_vida.idAplicacion = '.$idAplicacion.' AND reactivo.indice BETWEEN '.$a.' AND '.$b)->
					get('aplicacion_vida,reactivo')->
					result_array();
			$suma = $p[0]['valor'];
			return $suma;
		}

		public function insertarVida($idAplicacion,$R1,$R2,$R3,$R4,$R5,$R6,$R7,$R8,$R9,$R10,$R11,$R12){
			$this->db->query("INSERT INTO vida VALUES(NULL,$idAplicacion,$R1,$R2,$R3,$R4,$R5,$R6,$R7,$R8,$R9,$R10,$R11,$R12);");
		}
	}
 ?>