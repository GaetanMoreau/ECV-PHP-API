<?php

function getMovies(): array{
    require '../Service/Database.php';

    $sql = "SELECT * FROM movies";
    $getMoviesStmt = $db->prepare($sql);
    $getMoviesStmt->execute();

    return $getMoviesStmt->fetchAll(PDO::FETCH_ASSOC);
}

function getMovieById(int $id): array{
    require '../Service/Database.php';

    $sql = "SELECT * FROM movies WHERE id = :id";
    $getMovieByIdStmt = $db->prepare($sql);
    $getMovieByIdStmt->execute([
        'id' => $id
    ]);

    return $getMovieByIdStmt->fetchAll(PDO::FETCH_ASSOC);
}

function createMovie($title, $releasedate, $plot, $runtime): array{
    require '../Service/Database.php';

    $sql = "SELECT MAX(id) FROM movies";
    $getMoviesCountStmt = $db->prepare($sql);
    $getMoviesCountStmt->execute();

    $lastId = $getMoviesCountStmt->fetch(PDO::FETCH_COLUMN);

    $sql = "INSERT INTO movies (`title`, `release_date`, `plot`, `runtime`) VALUES (:title, :releasedate, :plot, :runtime)";
    $createMovieStmt = $db->prepare($sql);
    $createMovieStmt->execute([
        'title' => $title,
        'releasedate' => $releasedate,
        'plot' => $plot,
        'runtime' => $runtime
    ]);

    return getMovieById($lastId);
}

function updateMovie(int $id, $title, $releasedate, $plot, $runtime): array{
    require '../Service/Database.php';

    $sql = "UPDATE movies SET title = :title, release_date = :releasedate, plot = :plot, runtime = :runtime WHERE id = :id";

    $updateMovieStmt =  $db->prepare($sql);
    $updateMovieStmt->execute([
        'id' => $id,
        'title' => $title,
        'releasedate' => $releasedate,
        'plot' => $plot,
        'runtime' => $runtime
    ]);

    return getMovieById($id);
}

function deleteMovie(int $id): void{
    require '../Service/Database.php';

    $sql = "DELETE FROM movies WHERE id = :id";

    $deleteMovieStmt =  $db->prepare($sql);
    $deleteMovieStmt->execute([
        'id' => $id
    ]);
}
