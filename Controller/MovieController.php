<?php

require '../Repository/MovieRepository.php';
require '../Repository/ActorRepository.php';
require '../Repository/DirectorRepository.php';
require '../Repository/GenreRepository.php';
require '../Utils/ValidationUtils.php';
$requestMethod = $_SERVER['REQUEST_METHOD'];
header('Content-Type: application/json');
$id = $_GET['id'] ?? null;
$parent = $_GET['parent'] ?? null;

switch($requestMethod){
    case "GET":
        if ($id){
            switch ($parent){
                case "actors":
                    $movies = getMoviesFromActor($id);
                    if($movies) {
                        http_response_code(200);
                        echo json_encode($movies);
                    } else {
                        http_response_code(404);
                        echo json_encode(['code' => 404, 'message' => "L'acteur avec l'id $id n'est dans aucun film"]);
                    }
                    return;
                case "directors":
                    $movies = getMoviesFromDirector($id);
                    if($movies) {
                        http_response_code(200);
                        echo json_encode($movies);
                    } else {
                        http_response_code(404);
                        echo json_encode(['code' => 404, 'message' => "Le directeur avec l'id $id n'est dans aucun film"]);
                    }
                    return;
                case "genres":
                    $movies = getMoviesFromGenre($id);
                    if($movies) {
                        http_response_code(200);
                        echo json_encode($movies);
                    } else {
                        http_response_code(404);
                        echo json_encode(['code' => 404, 'message' => "Le genre avec l'id $id n'est dans aucun film"]);
                    }
                    return;
            }
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
        $title = filter_var($data->title, FILTER_SANITIZE_STRING);
        $releasedate = filter_var($data->releasedate, FILTER_SANITIZE_NUMBER_INT);
        $plot = filter_var($data->plot, FILTER_SANITIZE_STRING);
        $runtime = filter_var($data->runtime, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/")));

        $validatedTitle = titleValidation($title);
        $validatedReleasedate = releasedateValidation($releasedate);
        $validatedPlot = plotValidation($plot);
        $validatedRuntime = runtimeValidation($runtime);
        if ($validatedTitle === false || $validatedReleasedate === false || $validatedPlot === false || $validatedRuntime === false){
            return;
        }
        if (!isset($data->title, $data->releasedate, $data->plot, $data->runtime)) {
            http_response_code(400);
            $error = ['error' => 400, 'message' => 'Veuillez renseigner tous les champs'];
            echo json_encode($error);
        } else {
            $movie = createMovie($data->title, $data->releasedate, $data->plot, $data->runtime);
            http_response_code(201);
            echo json_encode($movie);
        }
        break;
    case "PUT":
        $data = json_decode(file_get_contents('php://input'));
        $title = filter_var($data->title, FILTER_SANITIZE_STRING);
        $releasedate = filter_var($data->releasedate, FILTER_SANITIZE_NUMBER_INT);
        $plot = filter_var($data->plot, FILTER_SANITIZE_STRING);
        $runtime = filter_var($data->runtime, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^\d{4}-\d{2}-\d{2}$/")));
        if (!isset($data->title, $data->releasedate, $data->plot, $data->runtime)) {
            http_response_code(400);
            $error = ['error' => 400, 'message' => 'Veuillez renseigner tous les champs'];
            echo json_encode($error);
        } else {
            if ($id) {
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
