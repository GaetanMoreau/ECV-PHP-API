<?php

require '../Repository/ReviewRepository.php';
require '../Utils/ValidationUtils.php';
$requestMethod = $_SERVER['REQUEST_METHOD'];
header('Content-Type: application/json');
$id = $_GET['id'] ?? null;

switch($requestMethod){
    case "GET":
        if ($id){
            if(preg_match("/reviews\/\d+/", $_SERVER['REQUEST_URI'])) {
                $review = getReviewById($id);
                if($review) {
                    http_response_code(200);
                    echo json_encode($review);
                } else {
                    http_response_code(404);
                    echo json_encode(['code' => 404, 'message' => "La review avec l'id $id n'existe pas"]);
                }
            } else {
                $reviews = getReviewsFromMovie($id);
                if(!empty($reviews)) {
                    http_response_code(200);
                    echo json_encode($reviews);
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'Aucune review trouvé pour ce film']);
                }
            }
        } else{
            $reviews = getReviews();
            http_response_code(200);
            echo json_encode($reviews);
        }
        break;
    case "POST":
        $data = json_decode(file_get_contents('php://input'));
        $movieid = filter_var($data->movieid, FILTER_SANITIZE_NUMBER_INT);
        $username = filter_var($data->username, FILTER_SANITIZE_STRING);
        $content = filter_var($data->content, FILTER_SANITIZE_STRING);
        $date = filter_var($data->date, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^\d{4}-\d{2}-\d{2}$/")));

        $validatedMovieId = movieidValidation($movieid);
        $validatedUsername = usernameValidation($username);
        $validatedContent = contentValidation($content);
        $validatedDate = dateValidation($date);
        if ( $validatedMovieId === false || $validatedUsername === false || $validatedContent === false || $validatedDate === false){
            return;
        }
        if (!isset($data->movieid, $data->username, $data->content, $data->date)) {
            http_response_code(400);
            $error = ['error' => 400, 'message' => 'Veuillez renseigner tous les champs'];
            echo json_encode($error);
        } else {
            $review = createReview($data->movieid, $data->username, $data->content, $data->date);
            http_response_code(201);
            echo json_encode($review);
        }
        break;
    case "PUT":
        $data = json_decode(file_get_contents('php://input'));
        $movieid = filter_var($data->movieid, FILTER_SANITIZE_NUMBER_INT);
        $username = filter_var($data->username, FILTER_SANITIZE_STRING);
        $content = filter_var($data->content, FILTER_SANITIZE_STRING);
        $date = filter_var($data->date, FILTER_SANITIZE_NUMBER_INT);

        $validatedMovieId = movieidValidation($movieid);
        $validatedUsername = usernameValidation($username);
        $validatedContent = contentValidation($content);
        $validatedDate = dateValidation($date);
        if ( $validatedMovieId === false || $validatedUsername === false || $validatedContent === false || $validatedDate === false){
            return;
        }
        if (!isset($data->movieid, $data->username, $data->content, $data->date)) {
            http_response_code(400);
            $error = ['error' => 400, 'message' => 'Veuillez renseigner tous les champs'];
            echo json_encode($error);
        } else {
            if ($id) {
                $review = getReviewById($id);
                if(!$review){
                    http_response_code(404);
                    echo json_encode(['code' => 404, 'message' => "La review avec l'id $id n'existe pas"]);
                    return;
                }
                $review = updateReview($id, $data->movieid, $data->username, $data->content, $data->date);
                http_response_code(200);
                echo json_encode($review);
            } else {
                $error = ['error' => 400, 'message' => "Veuillez renseigner l'id de la review à modifier"];
                echo json_encode($error);
            }
        }
        break;
    case "DELETE":
        if ($id) {
            $review = getReviewById($id);
            if(!$review){
                http_response_code(404);
                echo json_encode(['code' => 404, 'message' => "La review avec l'id $id n'existe pas"]);
                return;
            }
            deleteReview($id);
            http_response_code(204);
        } else {
            http_response_code(400);
            $error = ['error' => 400, 'message' => "Veuillez renseigner l'id de la review à supprimer"];
            echo json_encode($error);
        }
        break;
}
