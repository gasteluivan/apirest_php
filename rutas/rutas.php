<?php

$arrayRutas = explode("/", $_SERVER['REQUEST_URI']);

if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $cursos = new ControladorCursos();
    $cursos->index($_GET['page']);
} else {

    if (count(array_filter($arrayRutas)) == 0) {
        $json = array(
            "Detalles" => "No encontrados",
        );
        echo  json_encode($json, true);
        return;
    } else {

        if (count(array_filter($arrayRutas)) == 1) {
            if (array_filter($arrayRutas)[1] == "registro") {
                if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
                    $datos = array(
                        "nombre" => $_POST["nombre"],
                        "apellido" => $_POST["apellido"],
                        "email" => $_POST["email"]
                    );
                    $registro = new ControladorClientes();
                    $registro->create($datos);
                } else {
                    $json = array(
                        "detalle" => "no encontrado"
                    );
                    echo json_encode($json, true);
                    return;
                }
            } else if (array_filter($arrayRutas)[1] == "cursos") {
                if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "GET") {
                    $cursos = new ControladorCursos();
                    $cursos->index(null);
                } else if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
                    $datos = array(
                        "titulo" => $_POST["titulo"],
                        "descripcion" => $_POST["descripcion"],
                        "instructor" => $_POST["instructor"],
                        "imagen" => $_POST["imagen"],
                        "precio" => $_POST["precio"]
                    );
                    $crear_curso = new ControladorCursos();
                    $crear_curso->create($datos);
                } else {
                    $json = array(
                        "detalle" => "Metodo no encontrado"
                    );
                    echo json_encode($json, true);
                    return;
                }
            } else {
                $json = array(
                    "detalle" => "no encontrado"
                );
                echo json_encode($json, true);
                return;
            }
        } else {
            // un solo curso
            if (array_filter($arrayRutas)[1] == "cursos" && is_numeric(array_filter($arrayRutas)[2])) {
                if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "GET") {
                    $mostrar_curso = new ControladorCursos();
                    $mostrar_curso->show(array_filter($arrayRutas)[2]);
                } else if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "PUT") {
                    $datos = array();
                    parse_str(file_get_contents('php://input'), $datos);
                    $editar_curso = new ControladorCursos();
                    $editar_curso->update(array_filter($arrayRutas)[2], $datos);
                } else if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "DELETE") {
                    $eliminar_curso = new ControladorCursos();
                    $eliminar_curso->eliminar(array_filter($arrayRutas)[2]);
                } else {
                    $json = array(
                        "detalle" => "no encontrado"
                    );
                    echo json_encode($json, true);
                    return;
                }
            } else {
                $json = array(
                    "detalle" => "no encontrado"
                );
                echo json_encode($json, true);
                return;
            }
        }
    }
}
