<?php

//TODO : trouver la bonne condition dans le controller
function getActorsFromMovie(int $id): array{
    require '../Service/Database.php';

    $sql = "";

    $getActorsFromMovieStmt = $db->prepare($sql);
    $getActorsFromMovieStmt->execute([
        'id' => $id
    ]);

    return $getActorsFromMovieStmt->fetchAll(PDO::FETCH_ASSOC);
}

function getActors(): array{
    require '../Service/Database.php';

    $sql = "SELECT * FROM actors";
    $getActorsStmt = $db->prepare($sql);
    $getActorsStmt->execute();

    return $getActorsStmt->fetchAll(PDO::FETCH_ASSOC);
}
//TODO : ne fonctionne pas erreur 405 not allowed
function getActorById(int $id): array{
    require '../Service/Database.php';

    $sql = "SELECT * FROM actors WHERE id = :id";
    $getActorByIdStmt = $db->prepare($sql);
    $getActorByIdStmt->execute([
        'id' => $id
    ]);

    return $getActorByIdStmt->fetchAll(PDO::FETCH_ASSOC);
}

function createActor($firstname, $lastname, $dob, $bio): array{
    require '../Service/Database.php';

    $sql = "INSERT INTO actors (`first_name`, `last_name`, `dob`, `bio`) VALUES (:firstname, :lastname, :dob, :bio)";
    $createActorStmt = $db->prepare($sql);
    $createActorStmt->execute([
        'firstname' => $firstname,
        'lastname' => $lastname,
        'dob' => $dob,
        'bio' => $bio
    ]);

    $sql = "SELECT MAX(id) FROM actors";
    $getActorsCountStmt = $db->prepare($sql);
    $getActorsCountStmt->execute();

    $lastId = $getActorsCountStmt->fetch(PDO::FETCH_COLUMN);

    return getActorById($lastId);
}
//TODO : ne fonctionne pas erreur 405 not allowed
function updateActor(int $id, $firstname, $lastname, $dob, $bio): array{
    require '../Service/Database.php';

    $sql = "UPDATE actors SET first_name = :firstname, last_name = :lastname, dob = :dob, bio = :bio WHERE id = :id";

    $updateActorStmt =  $db->prepare($sql);
    $updateActorStmt->execute([
        'id' => $id,
        'firstname' => $firstname,
        'lastname' => $lastname,
        'dob' => $dob,
        'bio' => $bio
    ]);

    return getActorById($id);
}
//TODO : ne fonctionne pas erreur 405 not allowed
function deleteActor(int $id): void{
    require '../Service/Database.php';

    $sql = "DELETE FROM actors WHERE id = :id";

    $deleteActorStmt =  $db->prepare($sql);
    $deleteActorStmt->execute([
        'id' => $id
    ]);
}
