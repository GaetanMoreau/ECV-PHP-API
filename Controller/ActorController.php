<?php

require '../Repository/ActorRepository.php';
$requestMethod = $_SERVER['REQUEST_METHOD'];
header('Content-Type: application/json');
$id = $_GET['id'] ?? null;

switch($requestMethod){
    case "GET":
        if ($id){
            $actor = getActorById($id);
            if($actor) {
                http_response_code(200);
                echo json_encode($actor);
            } else {
                http_response_code(404);
                echo json_encode(['code' => 404, 'message' => "L'acteur avec l'id $id n'existe pas"]);
            }
        } else{
            $actors = getActors();
            http_response_code(200);
            echo json_encode($actors);
        }

        /*$actors = getActorsFromMovie($id);
        if(!empty($actors)) {
            http_response_code(200);
            echo json_encode($actors);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Aucun acteur trouvé pour ce film']);
        }*/

        break;
    case "POST":
        $data = json_decode(file_get_contents('php://input'));
        if (!isset($data->firstname, $data->lastname, $data->dob, $data->bio)) {
            http_response_code(400);
            $error = ['error' => 400, 'message' => 'Veuillez renseigner tous les champs'];
            echo json_encode($error);
        } else {
            // Valider les données avant d'insérer
            $actor = createActor($data->firstname, $data->lastname, $data->dob, $data->bio);
            http_response_code(201);
            echo json_encode($actor);
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
                $actor = getActorById($id);
                if(!$actor){
                    http_response_code(404);
                    echo json_encode(['code' => 404, 'message' => "L'acteur avec l'id $id n'existe pas"]);
                    return;
                }
                $actor = updateActor($id, $data->firstname, $data->lastname, $data->dob, $data->bio);
                http_response_code(200);
                echo json_encode($actor);
            } else {
                $error = ['error' => 400, 'message' => "Veuillez renseigner l'id de l'acteur à modifier"];
                echo json_encode($error);
            }
        }
        break;
    case "DELETE":
        if ($id) {
            $actor = getActorById($id);

            if(!$actor){
                http_response_code(404);
                echo json_encode(['code' => 404, 'message' => "L'acteur avec l'id $id n'existe pas"]);
                return;
            }

            deleteActor($id);
            http_response_code(204);

        } else {
            http_response_code(400);
            $error = ['error' => 400, 'message' => "Veuillez renseigner l'id de l'acteur à supprimer"];
            echo json_encode($error);
        }
        break;
}
