<?php

//TODO : trouver la bonne condition dans le controller
function getDirectorsFromMovie(int $id): array{
    require '../Service/Database.php';

    $sql = "";

    $getDirectorsFromMovieStmt = $db->prepare($sql);
    $getDirectorsFromMovieStmt->execute([
        'id' => $id
    ]);

    return $getDirectorsFromMovieStmt->fetchAll(PDO::FETCH_ASSOC);
}

function getDirectors(): array{
    require '../Service/Database.php';

    $sql = "SELECT * FROM directors";
    $getDirectorsStmt = $db->prepare($sql);
    $getDirectorsStmt->execute();

    return $getDirectorsStmt->fetchAll(PDO::FETCH_ASSOC);
}

function getDirectorById(int $id): array{
    require '../Service/Database.php';

    $sql = "SELECT * FROM directors WHERE id = :id";
    $getDirectorByIdStmt = $db->prepare($sql);
    $getDirectorByIdStmt->execute([
        'id' => $id
    ]);

    return $getDirectorByIdStmt->fetchAll(PDO::FETCH_ASSOC);
}

function createDirector($firstname, $lastname, $dob, $bio): array{
    require '../Service/Database.php';

    $sql = "INSERT INTO directors (`first_name`, `last_name`, `dob`, `bio`) VALUES (:firstname, :lastname, :dob, :bio)";
    $createDirectorStmt = $db->prepare($sql);
    $createDirectorStmt->execute([
        'firstname' => $firstname,
        'lastname' => $lastname,
        'dob' => $dob,
        'bio' => $bio
    ]);

    $sql = "SELECT MAX(id) FROM directors";
    $getDirectorsCountStmt = $db->prepare($sql);
    $getDirectorsCountStmt->execute();

    $lastId = $getDirectorsCountStmt->fetch(PDO::FETCH_COLUMN);

    return getDirectorById($lastId);
}

function updateDirector(int $id, $firstname, $lastname, $dob, $bio): array{
    require '../Service/Database.php';

    $sql = "UPDATE directors SET first_name = :firstname, last_name = :lastname, dob = :dob, bio = :bio WHERE id = :id";

    $updateDirectorStmt =  $db->prepare($sql);
    $updateDirectorStmt->execute([
        'id' => $id,
        'firstname' => $firstname,
        'lastname' => $lastname,
        'dob' => $dob,
        'bio' => $bio
    ]);

    return getDirectorById($id);
}

function deleteDirector(int $id): void{
    require '../Service/Database.php';

    $sql = "DELETE FROM directors WHERE id = :id";

    $deleteDirectorStmt =  $db->prepare($sql);
    $deleteDirectorStmt->execute([
        'id' => $id
    ]);
}
