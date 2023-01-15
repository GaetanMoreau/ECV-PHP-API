<?php

require '../Repository/DirectorRepository.php';
$requestMethod = $_SERVER['REQUEST_METHOD'];
header('Content-Type: application/json');
$id = $_GET['id'] ?? null;

switch($requestMethod){
    case "GET":
        if ($id){
            if(preg_match("/directors\/\d+\/movies", $_SERVER['REQUEST_URI'])) {
                $movies = getMoviesFromDirector($id);
                if($movies) {
                    http_response_code(200);
                    echo json_encode($movies);
                } else {
                    http_response_code(404);
                    echo json_encode(['code' => 404, 'message' => "Aucun film trouvé pour le directeur $id"]);
                }
            }
            elseif(preg_match("/directors\/\d+/", $_SERVER['REQUEST_URI'])) {
                $director = getDirectorById($id);
                if($director) {
                    http_response_code(200);
                    echo json_encode($director);
                } else {
                    http_response_code(404);
                    echo json_encode(['code' => 404, 'message' => "Le directeur avec l'id $id n'existe pas"]);
                }
            } else {
                $directors = getDirectorsFromMovie($id);
                if(!empty($directors)) {
                    http_response_code(200);
                    echo json_encode($directors);
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'Aucun directeur trouvé pour ce film']);
                }
            }
        } else{
            $directors = getDirectors();
            http_response_code(200);
            echo json_encode($directors);
        }
        break;
    case "POST":
        $data = json_decode(file_get_contents('php://input'));
        if (!isset($data->firstname, $data->lastname, $data->dob, $data->bio)) {
            http_response_code(400);
            $error = ['error' => 400, 'message' => 'Veuillez renseigner tous les champs'];
            echo json_encode($error);
        } else {
            // Valider les données avant d'insérer
            $director = createDirector($data->firstname, $data->lastname, $data->dob, $data->bio);
            http_response_code(201);
            echo json_encode($director);
        }
        break;
    case "PUT":
        $data = json_decode(file_get_contents('php://input'));
        if (!isset($data->firstname, $data->lastname, $data->dob, $data->bio)) {
            http_response_code(400);
            $error = ['error' => 400, 'message' => 'Veuillez renseigner tous les champs'];
            echo json_encode($error);
        } else {
            if ($id) {
                // Valider les données avant d'insérer
                $director = getDirectorById($id);
                if(!$director){
                    http_response_code(404);
                    echo json_encode(['code' => 404, 'message' => "Le directeur avec l'id $id n'existe pas"]);
                    return;
                }
                $director = updateDirector($id, $data->firstname, $data->lastname, $data->dob, $data->bio);
                http_response_code(200);
                echo json_encode($director);
            } else {
                $error = ['error' => 400, 'message' => "Veuillez renseigner l'id du directeur à modifier"];
                echo json_encode($error);
            }
        }
        break;
    case "DELETE":
        if ($id) {
            $director = getDirectorById($id);

            if(!$director){
                http_response_code(404);
                echo json_encode(['code' => 404, 'message' => "Le directeur avec l'id $id n'existe pas"]);
                return;
            }

            deleteDirector($id);
            http_response_code(204);

        } else {
            http_response_code(400);
            $error = ['error' => 400, 'message' => "Veuillez renseigner l'id du directeur à supprimer"];
            echo json_encode($error);
        }
        break;
}
