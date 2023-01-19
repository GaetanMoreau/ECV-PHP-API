<?php

require '../Repository/GenreRepository.php';
$requestMethod = $_SERVER['REQUEST_METHOD'];
header('Content-Type: application/json');
$id = $_GET['id'] ?? null;

switch($requestMethod){
    case "GET":
        if ($id){
            if(preg_match("/genres\/\d+/", $_SERVER['REQUEST_URI'])) {
                $genre = getGenreById($id);
                if($genre) {
                    http_response_code(200);
                    echo json_encode($genre);
                } else {
                    http_response_code(404);
                    echo json_encode(['code' => 404, 'message' => "Le genre avec l'id $id n'existe pas"]);
                }
            } else {
                $genres = getGenresFromMovie($id);
                if(!empty($genres)) {
                    http_response_code(200);
                    echo json_encode($genres);
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'Aucun genre trouvé pour ce film']);
                }
            }
        } else{
            $genres = getGenres();
            http_response_code(200);
            echo json_encode($genres);
        }
        break;
    case "POST":
        $data = json_decode(file_get_contents('php://input'));
        if (!isset($data->name)) {
            http_response_code(400);
            $error = ['error' => 400, 'message' => 'Veuillez renseigner tous les champs'];
            echo json_encode($error);
        } else {
            $genre = createGenre($data->name);
            http_response_code(201);
            echo json_encode($genre);
        }
        break;
    case "PUT":
        $data = json_decode(file_get_contents('php://input'));
        if (!isset($data->name)) {
            http_response_code(400);
            $error = ['error' => 400, 'message' => 'Veuillez renseigner tous les champs'];
            echo json_encode($error);
        } else {
            if ($id) {
                $genre = getGenreById($id);
                if(!$genre){
                    http_response_code(404);
                    echo json_encode(['code' => 404, 'message' => "Le genre avec l'id $id n'existe pas"]);
                    return;
                }
                $genre = updateGenre($id, $data->name);
                http_response_code(200);
                echo json_encode($genre);
            } else {
                $error = ['error' => 400, 'message' => "Veuillez renseigner l'id du genre à modifier"];
                echo json_encode($error);
            }
        }
        break;
    case "DELETE":
        if ($id) {
            $genre = getGenreById($id);
            if(!$genre){
                http_response_code(404);
                echo json_encode(['code' => 404, 'message' => "Le genre avec l'id $id n'existe pas"]);
                return;
            }
            deleteGenre($id);
            http_response_code(204);
        } else {
            http_response_code(400);
            $error = ['error' => 400, 'message' => "Veuillez renseigner l'id du genre à supprimer"];
            echo json_encode($error);
        }
        break;
}
