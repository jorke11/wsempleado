<?php

class Conexion {

    private $usuario;
    private $movimientos;
    private $soluciones;

    public function __construct() {
        $this->Conectar();
        $this->usuario = array();
        $this->movimientos = array();
        $this->soluciones = array();
        date_default_timezone_set("America/Bogota");
    }

    public function Conectar() {
//        $con = pg_connect("host=localhost port=5432 dbname=empleado user=postgres password=123");
        $con = pg_connect("host=empleado.cddjetfe34nc.us-east-2.rds.amazonaws.com port=5432 dbname=empleado user=empleadp password=empleado2018");
        return $con;
    }

    public function insertar($tabla, $datos, $debug = NULL) {
        $indice = '';
        $valor = '';

        foreach ($datos as $key => $value) {
            $indice .= ($indice == '') ? '' : ',';
            $valor .= ($valor == '') ? '' : ',';
            $indice .= '"' . $key . '"';
            $valor .= "'" . $value . "'";
        }

        $sql = "INSERT INTO $tabla($indice) VALUES($valor) RETURNING id;";

        $res = pg_query($sql);
        $insert_row = pg_fetch_row($res);
        $insert_id = $insert_row[0];
        return $insert_id;
    }

    public function obtenerDatos($tabla, $campos, $where, $debug = NULL) {
        $resultado = '';
        $where = ($where == NULL) ? '' : 'WHERE ' . $where;
        $sql = "
			SELECT $campos
			FROM $tabla
			$where
			";
        $res = pg_query($sql);

        while ($row = pg_fetch_assoc($res)) {
            $resultado[] = $row;
        }

        return $resultado;
    }

    public function query($sql) {
        $res = pg_query($sql);

        while ($row = pg_fetch_assoc($res)) {
            $resultado[] = $row;
        }

        return $resultado;
    }

    public function obtenerDatosId($tabla, $campos, $where, $debug = NULL) {
        $resultado = '';
        $where = ($where == NULL) ? '' : 'WHERE ' . $where;
        $sql = "
			SELECT $campos
			FROM $tabla
			$where
			";
        if ($debug == NULL) {
            $res = pg_query($sql);
            $row = pg_fetch_assoc($res);
            $resultado = $row;

            return $resultado;
        } else {
            return $sql;
        }
    }

    public function update($tabla, $id, $valor, $debug = NULL) {

        $contenido = '';
        foreach ($valor as $key => $value) {
            $contenido .= ($contenido == '') ? '' : ',';
            $contenido .= '"' . $key . '"=\'' . $value . '\'';
        }

        $sql = "
			UPDATE $tabla
			SET $contenido
			WHERE id =" . $id;

        if ($debug == NULL) {
            pg_query($sql);
            return $id;
        } else {
            return $sql;
        }
    }

    public function validaUsuario() {
        $sql = "
			SELECT *
			FROM usuarios
			";
        $res = pg_query($sql);

        if ($row = pg_fetch_assoc($res)) {
            $this->usuario[] = $row;
        }

        return !empty($this->usuario) ? $this->usuario : 'no';
    }

    public function consultarSaldo($numeroTar) {
        $sql = "
			SELECT *
			FROM movimientos
			WHERE tarjeta=$numeroTar
		";
        $res = mysql_query($sql);

        while ($row = mysql_fetch_assoc($res)) {
            $this->movimientos[] = $row;
        }

        return $this->movimientos;
    }

    public function informacionSoluciones($id) {
        $sql = "
			SELECT sol.id,user.usuario,sol.solucion
			FROM soluciones sol
			JOIN usuarios user ON sol.usuario=user.id
			WHERE sol.usuario=$id
		";
        $res = mysql_query($sql);

        while ($row = mysql_fetch_assoc($res)) {
            $this->soluciones[] = $row;
        }

        return $this->soluciones;
    }

    public function LimpiaMensaje($string) {

        $string = trim($string);

        $string = str_replace(
                array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä', 'Ã'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A', 'A'), $string
        );

        $string = str_replace(
                array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string
        );

        $string = str_replace(
                array('í', 'ì', 'ï', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string
        );

        $string = str_replace(
                array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string
        );

        $string = str_replace(
                array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string
        );

        $string = str_replace(
                array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C',), $string
        );


        $string = str_replace(
                array("\\", "¨", "º", "–", "~", "|", "·",
            "¡", "[", "^", "`", "]", "¨", "´", "¿",
            '§', '¤', '¥', 'Ð', 'Þ'), '', $string
        );

        $string = str_replace(
                array("&#39;",), array("'"), $string
        );
        return $string;
    }

}

?>