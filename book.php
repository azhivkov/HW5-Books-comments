<?php
$Pagetitle = 'Книги и коментари| book.php'; //промелива за html tag TITLE
$h1_tag = 'Коментари';  //промелнива за H1 tag
$background = 'img/bg.jpg'; // променлива за пътя до background-image
$style = 'styles/styles.css'; //промелнива за пътя до файла с стиловете
include 'includes/heder.php'; //Всички горни промелниви се използват в header.php и управляват изгледа на страницата
include 'includes/connection.php'; // Свързваме се към базата данни
include 'includes/functions.php';
// Ако имаме параметър от GET и той е реален оставаме в тази страница.
// Ако нямаме параметър или този параметър не съществува в БД се връщаме в index.php
// Изпълнено със следващият IF
if (isset($_GET['book-id']) AND IsBookExist($connection, $_GET['book-id'])) {
    $book_id = (int) $_GET['book-id'];

// НАЧАЛО Запис на коментара към базата данни
    if ($_POST) {
        // Тук може да се сложи проверка за дължина на коментара, но по-късно
        $msg = mysqli_real_escape_string($connection, trim($_POST['txt']));
        $query = 'INSERT INTO comments (text, date_published, user_id, book_id)
    VALUES ("' . $msg . '","' . date("Y-m-d H:i:s") . '","'
                . $_SESSION['user_id'] . '","' . $book_id . '")';
        mysqli_query($connection, $query);
    }
// КРАЙ   Запис на коментара към базата данни
// НАЧАЛО Извеждане на коменарите за книгата
    // Заявката която връща книга и автори когато няма коментари
    $query = mysqli_query($connection, 'SELECT * FROM books AS b
LEFT JOIN books_authors AS ba ON ba.book_id = b.book_id
LEFT JOIN authors AS a ON ba.author_id = a.author_id
LEFT JOIN comments AS c ON c.book_id = b.book_id
LEFT JOIN users AS u ON c.user_id = u.user_id
WHERE b.book_id ="' . $book_id . '"'
    );

// Заявката която връща нула резултати когато няма коментари    -- Отпада засега защото губя данните за книга и автори и не мога да ги попълня в таблицата.
//    $query = mysqli_query($connection, 'SELECT * FROM comments AS c
//INNER JOIN books AS b ON c.book_id = b.book_id
//INNER JOIN books_authors AS ba ON ba.book_id = b.book_id
//INNER JOIN authors AS a ON ba.author_id = a.author_id
//INNER JOIN users AS u ON c.user_id = u.user_id
//WHERE b.book_id ="' . $book_id . '"'
//    );
    $result = array();
$result2 = array();
    while ($row = mysqli_fetch_assoc($query)) {   // Правя два масива за нужните ми данни
        // Проверяваме дали за книгата има кометари. Ако има ги записваме в масива result.
        if (isset($row['comment_id'])) {
            $result[$row['comment_id']]['realname'] = $row['realname']; // Всяко едно comment_id е отделен подмасив съдържащ ..
            $result[$row['comment_id']]['user_id'] = $row['user_id'];   // Името на потребителя и user_id
            $result[$row['comment_id']]['text'] = $row['text'];         // Коментара който е направил за книгата
            $result[$row['comment_id']]['date_published'] = $row['date_published']; // Дата на публикуване
        }
        $result2['book_title'] = $row['book_title']; // Втори масив за книгата - Име на книгата
        $result2['authors'][$row['author_id']] = $row['author_name']; // Масив с ключ authors съдържащ в него ID за ключове и авторите 
    }
    // Извеждаме името на книгата и авторите
    echo '<div style="width:250px; float:left;"><h3>' . $result2['book_title'] . '</h3></div>
              <div style="width:250px;float:left;text-align:right"> от: ';
    $result_authors = array();
    foreach ($result2['authors'] as $key2 => $value2) {
        $result_authors[] = '<a href="index.php?author-id=' . $key2 . '" name="' . $value2 . '" title=" ' . $value2 . '">' . $value2 . '</a>';
    }
    echo implode(', ', $result_authors);
    echo '</div><br><br><br><br><br>';
    // Проверяваме дали масивът result e празен, Ако да - значи няма коментари
    if (empty($result)) {
        echo '<h4>За тази книга още няма коментари</h4>';
    }
    foreach ($result as $key => $value) {
// BEGIN ECHO TABLE
        echo '<table> 
                    <tr>
                        <td>
                        <a href="user.php?user-id=' . $value['user_id'] . '" name="' . $value['realname'] .
                        '" title=" ' . $value['realname'] . '">' . $value['realname'] . '</a>
                        </td>
                        <td>
                        ' . $value['date_published'] . '
                        </td>
                    </tr>
                        <tr>
                        <td colspan=2>
                        ' . $value['text'] . '
                        </td>
                    </tr>
                </table>
                <br>';
// END ECHO TABLE

    }

// КРАЙ Извеждане на коменарите за книгата
    if (isset($_SESSION['isLogged']) && $_SESSION['isLogged'] == TRUE) {
        echo '<h3>Здравей ' . $_SESSION['realname'] . '.</h3>'; // Обръщаме се персонално към потребителя
        ?> 
        <form method="POST">
            <textarea name="txt" placeholder="Оставете коментар за книгата" required="required"></textarea><br>
            <input type="submit" value="Коментирай"/>   <br>
        </form>
        <a href="logout.php" style="text-decoration: none;"><input type="button" value="Изход" /></a>
<a href="index.php" style="text-decoration: none;"><input type="button" value="Всички книги" /></a><br>
        <?php
//echo '<a href="logout.php" style="text-decoration: none;"><input type="button" value="Изход" /></a>';
    } else {
        echo '<a href="login.php" style="text-decoration: none;"><input type="button" value="Вход" /></a>';
    }
} else {
    header('Location: index.php');
}
?>
<a href="index.php" style="text-decoration: none;"><input type="button" value="Всички книги" /></a><br>
 <?php
include 'includes/footer.php';
?>