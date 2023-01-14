<?php

require '../Repository/MovieRepository.php';
$requestMethod = $_SERVER['REQUEST_METHOD'];
header('Content-Type: application/json');
$id = $_GET['id'] ?? null;

switch($requestMethod){
    case "GET":
        if ($id){
            $movie = getMovieById($id);
            if($movie) {
                http_response_code(200);
                echo json_encode($movie);
            } else {
                http_response_code(404);
                echo json_encode(['code' => 404, 'message' => "Le film avec l'id $id n'existe pas"]);
            }
        } else{
            $movies = getMovies();
            http_response_code(200);
            echo json_encode($movies);
        }
        break;
    case "POST":
        $data = json_decode(file_get_contents('php://input'));
        if (!isset($data->title, $data->releasedate, $data->plot, $data->runtime)) {
            http_response_code(400);
            $error = ['error' => 400, 'message' => 'Veuillez renseigner tous les champs'];
            echo json_encode($error);
        } else {
            // Valider les données avant d'insérer
            $movie = createMovie($data->title, $data->releasedate, $data->plot, $data->runtime);
            http_response_code(201);
            echo json_encode($movie);
        }
        break;
    case "PUT":
        $data = json_decode(file_get_contents('php://input'));
        if (!isset($data->title, $data->releasedate, $data->plot, $data->runtime)) {
            http_response_code(400);
            $error = ['error' => 400, 'message' => 'Veuillez renseigner tous les champs'];
            echo json_encode($error);
        } else {
            if ($id) {
                // Valider les données avant d'insérer
                $movie = getMovieById($id);
                if(!$movie){
                    http_response_code(404);
                    echo json_encode(['code' => 404, 'message' => "Le film avec l'id $id n'existe pas"]);
                    return;
                }
                $movie = updateMovie($id, $data->title, $data->releasedate, $data->plot, $data->runtime);
                http_response_code(200);
                echo json_encode($movie);
            } else {
                $error = ['error' => 400, 'message' => "Veuillez renseigner l'id du film à modifier"];
                echo json_encode($error);
            }
        }
        break;
    case "DELETE":
        if ($id) {
            $movie = getMovieById($id);

            if(!$movie){
                http_response_code(404);
                echo json_encode(['code' => 404, 'message' => "Le film avec l'id $id n'existe pas"]);
                return;
            }

            deleteMovie($id);
            http_response_code(204);

        } else {
            http_response_code(400);
            $error = ['error' => 400, 'message' => "Veuillez renseigner l'id du film à supprimer"];
            echo json_encode($error);
        }
        break;
}
