<?php

require '../Repository/MovieRepository.php';

function firstnameValidation($firstname){
    if(!preg_match("/^[a-zA-ZÀ-ÿ\s-]+$/", $firstname)) {
        http_response_code(400);
        $error = ['error' => 400, 'message' => 'Le prénom ne doit contenir que des lettres'];
        echo json_encode($error);
        return false;
    }  elseif(strlen($firstname)>25){
        http_response_code(400);
        $error = ['error' => 400, 'message' => 'Le prénom ne doit pas dépasser 25 caractères'];
        echo json_encode($error);
        return false;
    }
}
function lastnameValidation($lastname){
    if(!preg_match("/^[a-zA-ZÀ-ÿ\s-]+$/", $lastname)) {
        http_response_code(400);
        $error = ['error' => 400, 'message' => 'Le nom ne doit contenir que des lettres'];
        echo json_encode($error);
        return false;
    }  elseif(strlen($lastname)>25){
        http_response_code(400);
        $error = ['error' => 400, 'message' => 'Le nom ne doit pas dépasser 25 caractères'];
        echo json_encode($error);
        return false;
    }
}
function bioValidation($bio){
    if(!$bio){
        http_response_code(400);
        $error = ['error' => 400, 'message' => 'Veuillez entrer une biographie'];
        echo json_encode($error);
        return false;
    }elseif(strlen($bio)>250){
        http_response_code(400);
        $error = ['error' => 400, 'message' => 'La biographie ne doit pas dépasser 250 caractères'];
        echo json_encode($error);
        return false;
    }
}
function dobValidation($dob){
    if(!$dob){
        http_response_code(400);
        $error = ['error' => 400, 'message' => 'Veuillez entrer une date valide yyyy-mm-dd'];
        echo json_encode($error);
        return false;
    }
}
function genreNameValidation($name){
    if(!$name){
        http_response_code(400);
        $error = ['error' => 400, 'message' => 'Veuillez entrer un nom valide'];
        echo json_encode($error);
        return false;
    } elseif(strlen($name)>25){
        http_response_code(400);
        $error = ['error' => 400, 'message' => 'Le nom ne doit pas dépasser 25 caractères'];
        echo json_encode($error);
        return false;
    }
}
function titleValidation($title){
    if(!$title){
        http_response_code(400);
        $error = ['error' => 400, 'message' => 'Veuillez entrer un titre'];
        echo json_encode($error);
        return false;
    }elseif(strlen($title)>250){
        http_response_code(400);
        $error = ['error' => 400, 'message' => 'Le titre ne doit pas dépasser 250 caractères'];
        echo json_encode($error);
        return false;
    }
}
function releasedateValidation($releasedate){
    if(!$releasedate){
        http_response_code(400);
        $error = ['error' => 400, 'message' => 'Veuillez entrer une date de sortie'];
        echo json_encode($error);
        return false;
    }
}
function plotValidation($plot){
    if(!$plot){
        http_response_code(400);
        $error = ['error' => 400, 'message' => 'Veuillez entrer un plot'];
        echo json_encode($error);
        return false;
    }elseif(strlen($plot)>250){
        http_response_code(400);
        $error = ['error' => 400, 'message' => 'Le plot ne doit pas dépasser 250 caractères'];
        echo json_encode($error);
        return false;
    }
}
function runtimeValidation($runtime){
    if(!$runtime){
        http_response_code(400);
        $error = ['error' => 400, 'message' => 'Veuillez entrer une durée valide 00:00:00'];
        echo json_encode($error);
        return false;
    }
}
function usernameValidation($username){
    if(!$username){
        http_response_code(400);
        $error = ['error' => 400, 'message' => 'Veuillez entrer un username'];
        echo json_encode($error);
        return false;
    }elseif(strlen($username)>25){
        http_response_code(400);
        $error = ['error' => 400, 'message' => 'Le username ne doit pas dépasser 25 caractères'];
        echo json_encode($error);
        return false;
    }
}
function contentValidation($content){
    if(!$content){
        http_response_code(400);
        $error = ['error' => 400, 'message' => 'Veuillez entrer le contenu de la review'];
        echo json_encode($error);
        return false;
    }
}
function dateValidation($date){
    if(!$date){
        http_response_code(400);
        $error = ['error' => 400, 'message' => 'Veuillez entrer une date valide yyyy-mm-dd'];
        echo json_encode($error);
        return false;
    }
}
function movieidValidation($movieid){
    $movieExist = getMovieById($movieid);
    if (!$movieExist){
        http_response_code(400);
        $error = ['error' => 400, 'message' => "Ce film n'existe pas"];
        echo json_encode($error);
        return false;
    }
    if(!$movieid){
        http_response_code(400);
        $error = ['error' => 400, 'message' => "Veuillez entrer l'id d'un film"];
        echo json_encode($error);
        return false;
    }
}