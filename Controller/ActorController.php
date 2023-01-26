<?php

require '../Repository/ActorRepository.php';
require '../Utils/ValidationUtils.php';
$requestMethod = $_SERVER['REQUEST_METHOD'];
header('Content-Type: application/json');
$id = $_GET['id'] ?? null;

switch($requestMethod){
    case "GET":
        if ($id){
            if(preg_match("/actors\/\d+/", $_SERVER['REQUEST_URI'])) {
                $actor = getActorById($id);
                if($actor) {
                    http_response_code(200);
                    echo json_encode($actor);
                } else {
                    http_response_code(404);
                    echo json_encode(['code' => 404, 'message' => "L'acteur avec l'id $id n'existe pas"]);
                }
            } else {
                $actors = getActorsFromMovie($id);
                if(!empty($actors)) {
                    http_response_code(200);
                    echo json_encode($actors);
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'Aucun acteur trouvé pour ce film']);
                }
            }
        } else{
            $actors = getActors();
            http_response_code(200);
            echo json_encode($actors);
        }
        break;
    case "POST":
        $data = json_decode(file_get_contents('php://input'));
        $firstname = filter_var($data->firstname, FILTER_SANITIZE_STRING);
        $lastname = filter_var($data->lastname, FILTER_SANITIZE_STRING);
        $dob = filter_var($data->dob, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^\d{4}-\d{2}-\d{2}$/")));
        $bio = filter_var($data->bio, FILTER_SANITIZE_STRING);

        $validatedFirstname = firstnameValidation($firstname);
        $validatedLastname = lastnameValidation($lastname);
        $validatedDob = dobValidation($dob);
        $validatedBio = bioValidation($bio);
        if ($validatedFirstname === false || $validatedLastname === false || $validatedDob === false || $validatedBio === false){
            return;
        }
        if (!isset($data->firstname, $data->lastname, $data->dob, $data->bio)) {
            http_response_code(400);
            $error = ['error' => 400, 'message' => 'Veuillez renseigner tous les champs'];
            echo json_encode($error);
        } else {
            $actor = createActor($data->firstname, $data->lastname, $data->dob, $data->bio);
            http_response_code(201);
            echo json_encode($actor);
        }
        break;
    case "PUT":
        $data = json_decode(file_get_contents('php://input'));
        $firstname = filter_var($data->firstname, FILTER_SANITIZE_STRING);
        $lastname = filter_var($data->lastname, FILTER_SANITIZE_STRING);
        $dob = filter_var($data->dob, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^\d{4}-\d{2}-\d{2}$/")));
        $bio = filter_var($data->bio, FILTER_SANITIZE_STRING);

        $validatedFirstname = firstnameValidation($firstname);
        $validatedLastname = lastnameValidation($lastname);
        $validatedDob = dobValidation($dob);
        $validatedBio = bioValidation($bio);
        if ($validatedFirstname === false || $validatedLastname === false || $validatedDob === false || $validatedBio === false){
            return;
        }
        if (!isset($data->firstname, $data->lastname, $data->dob, $data->bio)) {
            http_response_code(400);
            $error = ['error' => 400, 'message' => 'Veuillez renseigner tous les champs'];
            echo json_encode($error);
        } else {
            if ($id) {
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