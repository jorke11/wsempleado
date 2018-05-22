<?php

header('Access-Control-Allow-Origin: *');
date_default_timezone_set("America/Bogota");
require_once"nusoap/nusoap.php";

$server = new soap_server();

$url = "urn:servicioweb";

$server->configureWSDL("servicioweb", $url);

$entrada["cedula"] = "xsd:string";

$entradaint["id"] = "xsd:int";

$entrada2["id"] = "xsd:string";
$entrada3["descripcion"] = "xsd:string";
$entradaE["cedula"] = "xsd:string";
$entradaE["nombres"] = "xsd:string";
$entradaE["apellidos"] = "xsd:string";
$entradaE["telefono"] = "xsd:string";
$entradaE["direccion"] = "xsd:string";
$entradaE["cargo_id"] = "xsd:int";
$entradaE["contrato_id"] = "xsd:int";
$entradaE["hojavida_id"] = "xsd:int";
$entradaE["dependency_id"] = "xsd:int";
$entradaE["sueldo"] = "xsd:string";
$entradaE["usuario"] = "xsd:string";
$entradaE["clave"] = "xsd:string";
$entradaE["estado_laboral_id"] = "xsd:int";
$entradaE["role_id"] = "xsd:int";
$entradaL["usuario"] = "xsd:string";
$entradaL["clave"] = "xsd:string";


//$server->wsdl->addComplexType(
//        'informacion', 'ComplexType', 'struct', 'all', '', array(
//    'salida' => array('name' => 'salida', 'type' => 'xsd:string')
//        )
//);

$respuesta = array('respuesta' => 'xsd:string');


$server->register('login', $entradaL, $respuesta, $url);

$server->register('getRol', $entradaint, $respuesta, $url);
$server->register('setRol', $entrada3, $respuesta, $url);

$server->register('getCargo', $entradaint, $respuesta, $url);
$server->register('setCargo', $entrada3, $respuesta, $url);
$server->register('getEstadoLaboral', $entradaint, $respuesta, $url);
$server->register('setEstadoLaboral', $entrada3, $respuesta, $url);
$server->register('getContratos', $entradaint, $respuesta, $url);
$server->register('setContratos', $entrada3, $respuesta, $url);
$server->register('getEmpleado', $entrada, $respuesta, $url);
$server->register('setEmpleado', $entradaE, $respuesta, $url);

function login($usuario, $clave) {
    require_once"class/Conexion.php";
    $obj = new Conexion();

    $sql = "select id,nombres,apellidos from empleados where usuario='" . $usuario . "' and clave='" . $clave . "'";

    $response = $obj->query($sql);

    if (count($response) == 0) {
        $response = "Usuario no encontrado";
    } else {
        $response = $response[0];
    }
    return new soapval('return', 'xsd:string', json_encode($response));
}

function setContratos($id) {
    
}

function getContratos($id) {
    require_once"class/Conexion.php";
    $obj = new Conexion();

    $sql = "select * from contratos";

    if ($id != 0) {
        $sql .= " where id=" . $id;
    }

    $response["data"] = $obj->query($sql);

    if (count($response["data"]) > 0) {
        $response["status"] = true;
    } else {
        $response["status"] = false;
        $response["msg"] = "Contrato     no encontrado";
    }

    return new soapval('return', 'xsd:string', json_encode($response));
}

function setEstadoLaboral($description) {
    require_once"class/Conexion.php";
    $obj = new Conexion();


    $param["descripcion"] = $description;
    $response["data"] = $obj->insertar("estado_laboral", $param);

    if (count($response["data"]) > 0) {
        $response["status"] = true;
        $response["msg"] = "estado_laboral agregado";
    } else {
        $response["status"] = false;
        $response["msg"] = "estado_laboral no se pudo agregar";
    }

    return new soapval('return', 'xsd:string', json_encode($response));
}

function getEstadoLaboral($id) {
    require_once"class/Conexion.php";
    $obj = new Conexion();

    $sql = "select * from estado_laboral ";

    if ($id != 0) {
        $sql .= " where id=" . $id;
    }


    $response["data"] = $obj->query($sql);

    if (count($response["data"]) > 0) {
        $response["status"] = true;
    } else {
        $response["status"] = false;
        $response["msg"] = "Estado laboral no encontrado";
    }

    return new soapval('return', 'xsd:string', json_encode($response));
}

function setDependencia($description) {
    require_once"class/Conexion.php";
    $obj = new Conexion();


    $param["descripcion"] = $description;
    $response["data"] = $obj->insertar("dependencias", $param);

    if (count($response["data"]) > 0) {
        $response["status"] = true;
        $response["msg"] = "dependencias agregado";
    } else {
        $response["status"] = false;
        $response["msg"] = "dependencias no se pudo agregar";
    }

    return new soapval('return', 'xsd:string', json_encode($response));
}

function getDependencia($id) {

    require_once"class/Conexion.php";
    $obj = new Conexion();

    $sql = "select * from dependencias ";

    if ($id != 0) {
        $sql .= " where id=" . $id;
    }


    $response["data"] = $obj->query($sql);

    if (count($response["data"]) > 0) {
        $response["status"] = true;
    } else {
        $response["status"] = false;
        $response["msg"] = "Dependencia no encontrada";
    }

    return new soapval('return', 'xsd:string', json_encode($response));
}

function setCargo($description) {
    require_once"class/Conexion.php";
    $obj = new Conexion();


    $param["descripcion"] = $description;
    $cargo_id = $obj->insertar("cargos", $param);

    $sql = "select id,descripcion from cargos where id=" . $cargo_id;



    $response = $obj->query($sql);

    if (count($response) > 0) {
        $response = $response[0];
    } else {
        $response = "Cargo no se pudo agregar";
    }

    return new soapval('return', 'xsd:string', json_encode($response));
}

function getCargo($id) {
    require_once"class/Conexion.php";
    $obj = new Conexion();

    $sql = "select * from cargos ";

    if ($id != 0) {
        $sql .= " where id=" . $id;
    }

    $response = $obj->query($sql);

    if (count($response) == 0) {
        $response = "Cargo no encontrado";
    }

    return new soapval('return', 'xsd:string', json_encode($response));
}

function setRol($description) {
    require_once"class/Conexion.php";
    $obj = new Conexion();


    $param["descripcion"] = $description;
    $response["data"] = $obj->insertar("roles", $param);

    if (count($response["data"]) > 0) {
        $response["status"] = true;
        $response["msg"] = "roles agregado";
    } else {
        $response["status"] = false;
        $response["msg"] = "roles no se pudo agregar";
    }

    return new soapval('return', 'xsd:string', json_encode($response));
}

function getRol($id) {
    require_once"class/Conexion.php";
    $obj = new Conexion();

    $sql = "select * from roles ";

    if ($id != 0) {
        $sql .= " where id=" . $id;
    }


    $response["data"] = $obj->query($sql);

    if (count($response["data"]) > 0) {
        $response["status"] = true;
    } else {
        $response["status"] = false;
        $response["msg"] = "Rol no encontrado";
    }


    return new soapval('return', 'xsd:string', json_encode($response));
}

function setEmpleado($nombres, $apellidos, $cedula, $telefono, $direccion, $cargo_id, $contrato_id, $hojavida_id, $dependency_id, $sueldo, $usuario, $clave, $estado_laboral_id, $role_id) {
    require_once"class/Conexion.php";
    $obj = new Conexion();

    $param["nombres"] = $nombres;
    $param["apellidos"] = $apellidos;
    $param["cedula"] = $cedula;
    $param["telefono"] = $telefono;
    $param["direccion"] = $direccion;
    $param["cargo_id"] = $cargo_id;
    $param["contrato_id"] = $contrato_id;
    $param["hojavida_id"] = $hojavida_id;
    $param["dependencia_id"] = $dependency_id;
    $param["sueldo"] = $sueldo;
    $param["usuario"] = $usuario;
    $param["clave"] = $clave;
    $param["estado_laboral_id"] = $estado_laboral_id;
    $param["role_id"] = $role_id;

    $sql = "select * from roles where id=" . $role_id;
    $roles = $obj->query($sql);
    $sql = "select * from cargos id=" . $cargo_id;
    $roles = $obj->query($sql);
    $sql = "select * from contratos id=" . $contrato_id;
    $roles = $obj->query($sql);
    $sql = "select * from hojavida id=" . $hojavida_id;
    $roles = $obj->query($sql);
    $sql = "select * from pedencias id=" . $dependency_id;
    $roles = $obj->query($sql);
    $sql = "select * from estadolaboral id=" . $estado_laboral_id;
    $roles = $obj->query($sql);


    $empleado_id = $obj->insertar("empleados", $param);

    $sql = "select id,nombres,apellidos,cedula,telefono,direccion,cargo_id,contrato_id,usuario,clave from empleados where id=" . $empleado_id;

    $response = $obj->query($sql);

    if (count($response) > 0) {
        $response = $response[0];
    } else {
        $response = "Empleado no se pudo agregar";
    }

    return new soapval('return', 'xsd:string', json_encode($response));
}

function getEmpleado($cedula) {
    require_once"class/Conexion.php";
    $obj = new Conexion();
    $where = '';
    if ($cedula != "0") {
        $where = "WHERE e.cedula='" . $cedula . "'";
    }

    $sql = "
        select e.id,e.nombres,e.apellidos,e.cedula,e.telefono,e.direccion,c.descripcion as cargo,r.descripcion as rol,e.usuario,e.clave
        from empleados e
        JOIN cargos c ON c.id=e.cargo_id
        JOIN roles r ON r.id=e.role_id
        $where
        ";

//      return new soapval('return', 'xsd:string', json_encode($sql));
    $response = $obj->query($sql);

    if (count($response) == 0) {
        $response = "usuario no encontrado";
    } else {
        if ($cedula == 0) {
            $response = $response;
        } else {
            $response = $response[0];
        }
    }


    return new soapval('return', 'xsd:string', json_encode($response));
}

@$server->service(file_get_contents("php://input"));


//$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? file_get_contents('php://input') : '';
//$server->service($HTTP_RAW_POST_DATA);
?>
