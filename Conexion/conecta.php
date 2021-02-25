<?php
	Class Conexion{
		
		private $host = 'localhost';
		private $usuario = 'postgres';
		private $clave = '12913479';
		private $dbnombre = 'bdanz002';
		private $puerto = '5432';
		private $opcion = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=> PDO::FETCH_ASSOC);
		protected $con;
		
		public function abrircon(){
		 		try	{
					$this->con = new PDO("pgsql:host={$this->host};port={$this->puerto};dbname={$this->dbnombre}",$this->usuario,$this->clave, $this->opcion);
					return $this->con;
				}
				catch(PDOException $e)
				{
					echo "Ocurri� un problema con la conexi�n: " . $e->getMessage();
				}
 
        }
			
		public function cerrarcon(){
	   		$this->conn = null;
	 	}
			
	}
?>