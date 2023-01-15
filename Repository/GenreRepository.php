<?php

function getGenresFromMovie(int $id): array{
    require '../Service/Database.php';

    $sql = "SELECT * FROM genres
    JOIN movie_genres ON genres.id = movie_genres.genre_id
    JOIN movies ON movies.id = movie_genres.movie_id
    WHERE movies.id = :id";

    $getGenresFromMovieStmt = $db->prepare($sql);
    $getGenresFromMovieStmt->execute([
        'id' => $id
    ]);

    return $getGenresFromMovieStmt->fetchAll(PDO::FETCH_ASSOC);
}

function getGenres(): array{
    require '../Service/Database.php';

    $sql = "SELECT * FROM genres";
    $getGenresStmt = $db->prepare($sql);
    $getGenresStmt->execute();

    return $getGenresStmt->fetchAll(PDO::FETCH_ASSOC);
}

function getGenreById(int $id): array{
    require '../Service/Database.php';

    $sql = "SELECT * FROM genres WHERE id = :id";
    $getGenreByIdStmt = $db->prepare($sql);
    $getGenreByIdStmt->execute([
        'id' => $id
    ]);

    return $getGenreByIdStmt->fetchAll(PDO::FETCH_ASSOC);
}

function createGenre($name): array{
    require '../Service/Database.php';

    $sql = "INSERT INTO genres (`name`) VALUES (:name)";
    $createGenreStmt = $db->prepare($sql);
    $createGenreStmt->execute([
        'name' => $name,
    ]);

    $sql = "SELECT MAX(id) FROM genres";
    $getGenresCountStmt = $db->prepare($sql);
    $getGenresCountStmt->execute();

    $lastId = $getGenresCountStmt->fetch(PDO::FETCH_COLUMN);

    return getGenreById($lastId);
}

function updateGenre(int $id, $name): array{
    require '../Service/Database.php';

    $sql = "UPDATE genres SET name = :name WHERE id = :id";

    $updateGenreStmt =  $db->prepare($sql);
    $updateGenreStmt->execute([
        'id' => $id,
        'name' => $name
    ]);

    return getGenreById($id);
}

function deleteGenre(int $id): void{
    require '../Service/Database.php';

    $sql = "DELETE FROM genres WHERE id = :id";

    $deleteGenreStmt =  $db->prepare($sql);
    $deleteGenreStmt->execute([
        'id' => $id
    ]);
}
