<?php

//TODO : trouver la bonne condition dans le controller
function getReviewsFromMovie(int $id): array{
    require '../Service/Database.php';

    $sql = "";

    $getReviewsFromMovieStmt = $db->prepare($sql);
    $getReviewsFromMovieStmt->execute([
        'id' => $id
    ]);

    return $getReviewsFromMovieStmt->fetchAll(PDO::FETCH_ASSOC);
}

function getReviews(): array{
    require '../Service/Database.php';

    $sql = "SELECT * FROM reviews";
    $getReviewsStmt = $db->prepare($sql);
    $getReviewsStmt->execute();

    return $getReviewsStmt->fetchAll(PDO::FETCH_ASSOC);
}
//TODO : ne fonctionne pas erreur 405 not allowed
function getReviewById(int $id): array{
    require '../Service/Database.php';

    $sql = "SELECT * FROM reviews WHERE id = :id";
    $getReviewByIdStmt = $db->prepare($sql);
    $getReviewByIdStmt->execute([
        'id' => $id
    ]);

    return $getReviewByIdStmt->fetchAll(PDO::FETCH_ASSOC);
}

function createReview($movieid, $username, $content, $date): array{
    require '../Service/Database.php';

    $sql = "INSERT INTO reviews (`movie_id`, `username`,`content`, `date` ) VALUES (:movieid, :username, :content, :date)";
    $createReviewStmt = $db->prepare($sql);
    $createReviewStmt->execute([
        'movieid' => $movieid,
        'username' => $username,
        'content' => $content,
        'date' => $date
    ]);

    $sql = "SELECT MAX(id) FROM reviews";
    $getReviewsCountStmt = $db->prepare($sql);
    $getReviewsCountStmt->execute();

    $lastId = $getReviewsCountStmt->fetch(PDO::FETCH_COLUMN);

    return getReviewById($lastId);
}
//TODO : ne fonctionne pas erreur 405 not allowed
function updateReview(int $id, $movieid, $username, $content, $date): array{
    require '../Service/Database.php';

    $sql = "UPDATE reviews SET movie_id = :movieid, username = :username, content = :content, date = :date WHERE id = :id";

    $updateReviewStmt =  $db->prepare($sql);
    $updateReviewStmt->execute([
        'id' => $id,
        'movieid' => $movieid,
        'username' => $username,
        'content' => $content,
        'date' => $date
    ]);

    return getReviewById($id);
}
//TODO : ne fonctionne pas erreur 405 not allowed
function deleteReview(int $id): void{
    require '../Service/Database.php';

    $sql = "DELETE FROM reviews WHERE id = :id";

    $deleteReviewStmt =  $db->prepare($sql);
    $deleteReviewStmt->execute([
        'id' => $id
    ]);
}
