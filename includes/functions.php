<?php

include 'includes/connection.php'; // Свързваме се към базата данни

function get_authors() { // OK 
    global $connection;
    $query = "SELECT * FROM authors";
    $result = mysqli_query($connection, $query);
    $output = Array();
    if (!$result) {
        echo '<br> No Mysql query';
        //return false;
    }
    if (mysqli_num_rows($result) == 0) {
        echo '<br> No Authors<br>';
        //return Array(Array('name'=>''));
    } else {
        while ($row = $result->fetch_assoc()) {
            $output[$row['author_id']] = $row['author_name'];
        }
        return $output;
    }
}
function show_error($errors){
    if (count($errors) > 0) {
            foreach ($errors as $value) {
                echo '<h4>' . $value . '</h4>';
            }
        } else {
            return TRUE;
        }
}
function insert_book($connection, $title, $author_id) {
   // $title = mysqli_real_escape_string($connection, $title);
    // $author_id = mysqli_real_escape_string($connection, $author_id);
    $book_query = 'INSERT INTO books (book_title)
        VALUES("' . $title . '")';
    mysqli_query($connection, $book_query);
    $book_id = mysqli_insert_id($connection); // Взимаме последното book_ID генерирано от Mysql
    foreach ($author_id as $values) { // Това ID го записваме в таблицата books_authors 
        $book_authors_query = 'INSERT INTO books_authors  
                (book_id, author_id) VALUES
                ("' . $book_id . '","' . $values . '")'; // Като към него добавяме и authour_ID 
        mysqli_query($connection, $book_authors_query);
    }
    return TRUE;
}
function IsBookExist ($connection, $book_id)
{
    $query = mysqli_query($connection, 'SELECT * FROM books WHERE book_id="'.(int)$book_id.'"');
    if (mysqli_num_rows($query) > 0) {
              return TRUE;   //
        }
    return FALSE;
}
function IsUserExist ($connection, $user_id)
{
    $query = mysqli_query($connection, 'SELECT * FROM users WHERE user_id="'.(int)$user_id.'"');
    if (mysqli_num_rows($query) > 0) {
              return TRUE;   //
        }
    return FALSE;
}
?>