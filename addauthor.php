<?php
$Pagetitle = 'Добави автор | addauthor.php'; //промелива за html tag TITLE
$h1_tag = 'Добави автор';  //промелнива за H1 tag
$background = 'img/bg.jpg'; // променлива за пътя до background-image
$style = 'styles/styles.css'; //промелнива за пътя до файла с стиловете
include 'includes/heder.php'; //Всички горни промелниви се използват в header.php и управляват изгледа на страницата
include 'includes/functions.php'; // Свързваме се с функциите
?>
<a href="addbook.php" style="text-decoration: none;"><input type="button" value="Добави книга" /></a>
<a href="index.php" style="text-decoration: none;"><input type="button" value="Книги" /></a>
<form method="POST">
    <div><input type="text" name="author_name" placeholder="Автор: " required="required"/></div>
    <div><input type="submit" value="Добави"></div>
</form>
<?php
if ($_POST) {
    $error = array();
    $author_name = trim($_POST['author_name']);
    $author_name = strip_tags($author_name);
    if (mb_strlen($author_name) < 3) {
        $error[] = 'Името трябва да бъде поне 3 символа';
    }
    $author_name = mysqli_real_escape_string($connection, $author_name);
    $query = 'SELECT author_name FROM authors WHERE author_name="' . $author_name . '"';
    $result = mysqli_query($connection, $query);
    if (mysqli_num_rows($result) > 0) {
        $error[] = 'Въведеното от Вас име (' . $author_name . ') съществува в БД';
    }
    if (count($error) > 0) {
        foreach ($error as $val) {
            echo '<h4>' . $val . '</h4>';
        }
    } else {
        $query = 'INSERT INTO authors (author_name)
                VALUES("' . $author_name . '")';
        if (!mysqli_query($connection, $query)) {
            echo '<h4>Няма запис поради системна грешка!!!</h4>'; // :) 
        } else {
            echo '<h4>Автор "' . $author_name . '" е добавен успешно!!!</h4>';
        }
    }
}
include 'includes/footer.php';
?>