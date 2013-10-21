<?php
$Pagetitle = 'Добави книга | addbook.php'; //промелива за html tag TITLE
$h1_tag = 'Добави книга';  //промелнива за H1 tag
$background = 'img/bg.jpg'; // променлива за пътя до background-image
$style = 'styles/styles.css'; //промелнива за пътя до файла с стиловете
include 'includes/heder.php'; //Всички горни промелниви се използват в header.php и управляват изгледа на страницата
include 'includes/connection.php'; // Свързваме се към базата данни
include 'includes/functions.php'; // Свързваме се с функциите
?>
<a href="addauthor.php" style="text-decoration: none;"><input type="button" value="Добави aвтор" /></a>
<a href="index.php" style="text-decoration: none;"><input type="button" value="Книги" /></a>
<form method="GET">

    <div><input type="text" name="book_title" placeholder="Заглавие: " required="required"/></div>
    <div><select multiple name="authors[]" >

            <?php
            foreach (get_authors() as $key => $value) {
                echo '<option value="' . $key . '">' . $value . '</option>';
            }
            ?>
        </select> </div>
    <div><input type="submit" value="Добави"></div>
</form>
<?php
if ($_GET) {
    $book_title = trim($_GET['book_title']);
    $book_title = mysqli_real_escape_string($connection, (strip_tags($book_title)));
    $book_author_id = array();
    $error = array();
    if (!isset($_GET['authors'])) {
        echo '<h4>Изберете поне един автор!</h4>';
        exit;
    }
    $book_author_id = $_GET['authors'];
    if (mb_strlen($book_title) < 3) {
        $error[] = 'Заглавието на книгата трябва да бъде поне 3 символа';
    }

    if (count($error) > 0) {
        foreach ($error as $vl) {
            echo '<h4>' . $vl . '</h4>';
        }
    } else {
        $book_query = 'INSERT INTO books (book_title)
        VALUES("' . $book_title . '")';

        if (!mysqli_query($connection, $book_query)) {
            $error[] = 'Няма запис поради системна грешка 1!!!';
        }

        $book_id = mysqli_insert_id($connection); // Взимаме последното book_ID генерирано от Mysql
        foreach ($book_author_id as $values) { // Това ID го записваме в таблицата books_authors 
            $book_authors_query = 'INSERT INTO books_authors  
                (book_id, author_id) VALUES
                ("' . $book_id . '","' . $values . '")'; // Като към него добавяме и authour_ID 
            if (!mysqli_query($connection, $book_authors_query)) {
                $error[] = 'Няма запис поради системна грешка 2!!!';
            }
        }

        if (count($error) > 0) {
            foreach ($error as $val) {
                echo '<h4>' . $val . '</h4>';
            }
        } else {
            echo '<h4>Книгата е добавена</h4>';
        }
    }
}



include 'includes/footer.php';
?>