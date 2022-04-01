<?php

class ControladorClientes
{

    public function create($datos)
    {

        if (isset($datos["nombre"]) && !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚÑñ ]*$/", $datos["nombre"])) {
            $json = array(
                "status" => "404",
                "Detalles" => "Error en el nombre, solo se permiten caractres alfabeticos",
            );
            echo  json_encode($json, true);
            return;
        }
        if (isset($datos["apellido"]) && !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚÑñ ]*$/", $datos["apellido"])) {
            $json = array(
                "status" => "404",
                "Detalles" => "Error en el apellido, solo se permiten caractres alfabeticos",
            );
            echo  json_encode($json, true);
            return;
        }
        if (isset($datos["email"]) && !preg_match('/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/', $datos["email"])) {
            $json = array(
                "status" => "404",
                "Detalles" => "Error en el email, coloca un email valido",
            );
            echo  json_encode($json, true);
            return;
        }

        $cliente = ModelosClientes::index("clientes");
        foreach ($cliente as $key => $value) {
            if ($value["email"] == $datos["email"]) {
                $json = array(
                    "status" => "404",
                    "Detalles" => "El email ya existe",
                );
                echo  json_encode($json, true);
                return;
            }
        }

        // generar credencials del cliente
        $id_cliente = str_replace("$", "a", crypt($datos["nombre"] . $datos["apellido"] . $datos["email"], '$2a$07$usesomesillystringforsalt$'));
        $llave_secreta = str_replace("$", "o", crypt($datos["email"] . $datos["apellido"] . $datos["nombre"], '$2a$07$usesomesillystringforsalt$'));

        // $json = array(
        //     "id cliente" => $id_cliente,
        //     "llave screta" => $llave_secreta,
        // );
        // echo  json_encode($json, true);
        // return;

        // Find credenciales

        $datos = array(
            "id_cliente" => $id_cliente,
            "llave_secreta" => $llave_secreta,
            "nombre" => $datos["nombre"],
            "apellido" => $datos["apellido"],
            "email" => $datos["email"],
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s"),
        );

        $crear_registro = ModelosClientes::create("clientes", $datos);

        if ($crear_registro == "ok") {
            $json = array(
                "status" => "200",
                "Detalles" => "Registro creado correctamente",
                "id cliente" => $id_cliente,
                "llave screta" => $llave_secreta,
            );
            echo  json_encode($json, true);
            return;
        }
    }
}
