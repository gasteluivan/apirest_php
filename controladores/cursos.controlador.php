<?php

class ControladorCursos
{

    public function index($page)
    {
        $clientes = ModelosClientes::index("clientes");
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {

            foreach ($clientes as $key => $valueClientes) {
                if (
                    "Basic " . base64_encode($_SERVER['PHP_AUTH_USER'] . ":" . $_SERVER['PHP_AUTH_PW']) == "Basic " . base64_encode($valueClientes['id_cliente'] . ":" . $valueClientes['llave_secreta'])
                ) {

                    if ($page != null) {
                        $cantidad = 10;
                        $desde = ($page - 1) * $cantidad;
                        $cursos = ModelosCursos::index('cursos', 'clientes', $cantidad, $desde);
                    } else {
                        $cursos = ModelosCursos::index('cursos', 'clientes', null, null);
                    }
                    

                    if (!empty($cursos)) {
                        $json = array(
                            "status" => "200",
                            "total_registros" => count($cursos),
                            "Detalles" => $cursos,
                        );
                        echo  json_encode($json, true);
                        return;
                    } else {
                        $json = array(
                            "status" => "200",
                            "total_registros" => 0,
                            "Detalles" => 'No hay cursos registrados',
                        );
                        echo  json_encode($json, true);
                        return;
                    }
                } else {
                    $json = array(
                        "status" => "404",
                        "Detalles" => 'Token no valido',
                    );
                }
            }
        } else {
            $json = array(
                "status" => "404",
                "Detalles" => 'No estas autorizado para ver este contenido',
            );
        }
        echo  json_encode($json, true);
        return;
    }

    // crear curso
    public function create($datos)
    {
        $clientes = ModelosClientes::index("clientes");
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {

            foreach ($clientes as $key => $valueClientes) {
                if ("Basic " . base64_encode($_SERVER['PHP_AUTH_USER'] . ":" . $_SERVER['PHP_AUTH_PW']) == "Basic " . base64_encode($valueClientes['id_cliente'] . ":" . $valueClientes['llave_secreta'])) {

                    foreach ($datos as $key => $valueDatos) {
                        if (empty($valueDatos)) {
                            $json = array(
                                "status" => "404",
                                "Detalles" => "El valor " . $key . " esta vacio",
                            );
                            echo  json_encode($json, true);
                            return;
                        }
                        if (isset($valueDatos) && !preg_match("/^[(\\)\\=\\&\\$\\;\\-\\_\\*\\<\\>\\?\\¿\\¡\\!\\:\\,\\.\\0-9a-zA-ZáéíóúÁÉÍÓÚÑñ ]*$/", $valueDatos)) {
                            $json = array(
                                "status" => "404",
                                "Detalles" => "Error en el campo " . $key,
                            );
                            echo  json_encode($json, true);
                            return;
                        }
                    }

                    $cursos = ModelosCursos::index('cursos', 'clientes', null, null);
                    foreach ($cursos as $key => $valueCurso) {
                        if ($valueCurso->titulo == $datos["titulo"]) {
                            $json = array(
                                "status" => "404",
                                "Detalles" => "El curso: " . $valueCurso->titulo . " - ya existe",
                            );
                            echo  json_encode($json, true);
                            return;
                        }
                        if ($valueCurso->descripcion == $datos["descripcion"]) {
                            $json = array(
                                "status" => "404",
                                "Detalles" => "La descripcion ya existe",
                            );
                            echo  json_encode($json, true);
                            return;
                        }
                    }

                    $datos = array(
                        "titulo" => $datos["titulo"],
                        "descripcion" => $datos["descripcion"],
                        "instructor" => $datos["instructor"],
                        "imagen" => $datos["imagen"],
                        "precio" => $datos["precio"],
                        "id_creador" => $valueClientes["id"],
                        "created_at" => date("Y-m-d H:i:s"),
                        "updated_at" => date("Y-m-d H:i:s"),
                    );
                    $crear_registro = ModelosCursos::create("cursos", $datos);
                    if ($crear_registro == "ok") {
                        $json = array(
                            "status" => "200",
                            "Detalles" => "Curso creado correctamente",
                        );
                        echo  json_encode($json, true);
                        return;
                    } else {
                        $json = array(
                            "status" => "404",
                            "Detalles" => 'Algo salio mal',
                        );
                        echo  json_encode($json, true);
                        return;
                    }
                } else {
                    $json = array(
                        "status" => "404",
                        "Detalles" => 'Token no valido',
                    );
                }
            }
        } else {
            $json = array(
                "status" => "404",
                "Detalles" => 'No estas autorizado para crear cursos',
            );
        }
        echo  json_encode($json, true);
        return;

        // $json = array(
        //     "Detalles" => "Curso creado",
        // );
        // echo  json_encode($json, true);
        // return;
    }

    // buscar curso por id
    public function show($id)
    {
        $clientes = ModelosClientes::index("clientes");
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            foreach ($clientes as $key => $valueClientes) {
                if (
                    "Basic " . base64_encode($_SERVER['PHP_AUTH_USER'] . ":" . $_SERVER['PHP_AUTH_PW']) == "Basic " . base64_encode($valueClientes['id_cliente'] . ":" . $valueClientes['llave_secreta'])
                ) {
                    $curso = ModelosCursos::show('cursos', 'clientes', $id);
                    if (!empty($curso)) {
                        $json = array(
                            "status" => "200",
                            "Detalles" => $curso,
                        );
                        echo  json_encode($json, true);
                        return;
                    } else {
                        $json = array(
                            "status" => "200",
                            "Detalles" => 'No existe el curso con el id: ' . $id,
                        );
                        echo  json_encode($json, true);
                        return;
                    }
                } else {
                    $json = array(
                        "status" => "404",
                        "Detalles" => 'Token no valido',
                    );
                }
            }
        } else {
            $json = array(
                "status" => "404",
                "Detalles" => 'No estas autorizado para ver este contenido',
            );
        }
        echo  json_encode($json, true);
        return;
    }

    // actualizar datos de un curso
    public function update($id, $datos)
    {
        $cliente = ModelosClientes::index("clientes");
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {

            foreach ($cliente as $key => $valueClientes) {
                if (
                    "Basic " . base64_encode($_SERVER['PHP_AUTH_USER'] . ":" . $_SERVER['PHP_AUTH_PW']) ==
                    "Basic " . base64_encode($valueClientes['id_cliente'] . ":" . $valueClientes['llave_secreta'])
                ) {

                    foreach ($datos as $key => $valueDatos) {
                        if (empty($valueDatos)) {
                            $json = array(
                                "status" => "404",
                                "Detalles" => "El valor " . $key . " esta vacio",
                            );
                            echo  json_encode($json, true);
                            return;
                        } else if (isset($valueDatos) && !preg_match('/^[(\\)\\=\\&\\$\\;\\-\\_\\*\\"\\<\\>\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $valueDatos)) {
                            $json = array(
                                "status" => "404",
                                "Detalles" => "Error en el campo " . $key,
                            );
                            echo  json_encode($json, true);
                            return;
                        }
                    }

                    // Validar id creador
                    $curso = ModelosCursos::show('cursos', 'clientes', $id);
                    foreach ($curso as $key => $valueCurso) {
                        if ($valueCurso->id_creador == $valueClientes["id"]) {
                            $datos = array(
                                "id" => $id,
                                "titulo" => $datos["titulo"],
                                "descripcion" => $datos["descripcion"],
                                "instructor" => $datos["instructor"],
                                "imagen" => $datos["imagen"],
                                "precio" => $datos["precio"],
                                "updated_at" => date("Y-m-d H:i:s")
                            );

                            $update = ModelosCursos::update("cursos", $datos);

                            if ($update == "ok") {
                                $json = array(
                                    "status" => "200",
                                    "Detalles" => "Curso actualizado correctamente",
                                );
                                echo  json_encode($json, true);
                                return;
                            }
                        } else {
                            $json = array(
                                "status" => "404",
                                "Detalles" => 'No estas autorizado para actualizar este curso',
                            );
                            echo  json_encode($json, true);
                            return;
                        }
                    }
                } else {
                    $json = array(
                        "status" => "404",
                        "Detalles" => 'Token no valido para actualizar este curso',
                    );
                }
            }
        } else {
            $json = array(
                "status" => "404",
                "Detalles" => 'No estas autorizado para actualizar este curso',
            );
        }
        echo  json_encode($json, true);
        return;
    }

    public function eliminar($id)
    {
        $clientes = ModelosClientes::index("clientes");
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            foreach ($clientes as $key => $valueClientes) {
                if (
                    "Basic " . base64_encode($_SERVER['PHP_AUTH_USER'] . ":" . $_SERVER['PHP_AUTH_PW']) == "Basic " . base64_encode($valueClientes['id_cliente'] . ":" . $valueClientes['llave_secreta'])
                ) {
                    // Validar id creador
                    $curso = ModelosCursos::show('cursos', 'clientes', $id);

                    if (!empty($curso)) {
                        foreach ($curso as $key => $valueCurso) {
                            if ($valueCurso->id_creador == $valueClientes["id"]) {
                                $delete = ModelosCursos::delete("cursos", $id);
                                if ($delete == "ok") {
                                    $json = array(
                                        "status" => "200",
                                        "Detalles" => "Curso eliminado correctamente",
                                    );
                                    echo  json_encode($json, true);
                                    return;
                                }
                            } else {
                                $json = array(
                                    "status" => "404",
                                    "Detalles" => 'No estas autorizado para eliminar este curso',
                                );
                                echo  json_encode($json, true);
                                return;
                            }
                        }
                    } else {
                        $json = array(
                            "status" => "200",
                            "Detalles" => 'No existe el curso con el id: ' . $id,
                        );
                        echo  json_encode($json, true);
                        return;
                    }
                } else {
                    $json = array(
                        "status" => "404",
                        "Detalles" => 'Token no valido',
                    );
                }
            }
        } else {
            $json = array(
                "status" => "404",
                "Detalles" => 'No estas autorizado para ver este contenido',
            );
        }
        echo  json_encode($json, true);
        return;
    }
}
