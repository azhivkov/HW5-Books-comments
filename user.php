<?php
$Pagetitle = 'Профил | user.php'; //промелива за html tag TITLE
$h1_tag = 'Коментари публикувани от ';  //промелнива за H1 tag
$background = 'img/bg.jpg'; // променлива за пътя до background-image
$style = 'styles/styles.css'; //промелнива за пътя до файла с стиловете
include 'includes/heder.php'; //Всички горни промелниви се използват в header.php и управляват изгледа на страницата
include 'includes/connection.php'; // Свързваме се към базата данни
include 'includes/functions.php';
// Ако имаме параметър от GET за user_id и той е реален оставаме в тази страница.
// Ако нямаме параметър или този параметър не съществува в БД се връщаме в index.php
// Изпълнено със следващият IF
if (isset($_GET['user-id']) AND IsUserExist($connection, $_GET['user-id'])) {
    $user_id = (int) $_GET['user-id'];


// НАЧАЛО Извеждане на всички коментари които потребителя е оставил за книгите
    // 
    $query = mysqli_query($connection, 'SELECT 
*
FROM users AS u 
INNER JOIN comments AS c ON c.user_id=u.user_id
INNER JOIN books AS b ON b.book_id=c.book_id
INNER JOIN books_authors AS ba ON ba.book_id=b.book_id
INNER JOIN authors AS a ON a.author_id=ba.author_id
WHERE u.user_id="' . $user_id . '"'
    );
 //   echo 'QUERY<br>';
 //   echo '<pre>' . print_r($query, TRUE) . '</pre>';
    $result = array();
    while ($row = mysqli_fetch_assoc($query)) {   // Правя два масива за нужните ми данни
        // Проверяваме дали за книгата има кометари. Ако има ги записваме в масива result.
     //   echo '$row <br>';
     //   echo '<pre>' . print_r($row, TRUE) . '</pre>';
        $realname = $row['realname'];
// $h1_tag = 'Профил на '.$realname; // TO DO - да добавя в заглавието името на автора.
        //  $result[$row['comment_id']]['realname'] = $row['realname']; // Всяко едно comment_id е отделен подмасив съдържащ ..
        //  $result[$row['comment_id']]['user_id'] = $row['user_id'];   // Името на потребителя и user_id
        $result[$row['comment_id']]['text'] = $row['text'];         // Коментара който е направил за книгата
        $result[$row['comment_id']]['date_published'] = $row['date_published']; // Дата на публикуване
        $result[$row['comment_id']]['book_title'] = $row['book_title']; // Име на книгата
        $result[$row['comment_id']]['authors'][$row['author_id']] = $row['author_name']; // Масив с ключ authors съдържащ в него ID за ключове и авторите 
    }
    // Извеждаме името на книгата и авторите
   // echo '$result <br>';
   // echo '<pre>' . print_r($result, TRUE) . '</pre>';
echo '<div><h3>' . $realname . '</h3></div><br>';

    // Проверяваме дали масивът result e празен, Ако да - значи няма коментари
    if (empty($result)) {
        echo '<h4>За тази книга още няма коментари</h4>';
    }
    
    foreach ($result as $key => $value) {
// BEGIN ECHO TABLE
        echo '<table> 
                <tr>
                    <td colspan=3> 
                    ' . $value['text'] . '
                    </td>                        
                </tr>
                <tr>
                    <td width=25%>за книга:<br>  <a href="book.php?book-id=' . $key . '" name="' . $value['book_title'] . '"
                    title= "' . $value['book_title'] . '">' . $value['book_title'] . '</a>
                    </td>
                <td width=40%>с автори: ';
        $result_authors = array();
        foreach ($value['authors'] as $key2 => $value2) {
        $result_authors[] = '<a href="index.php?author-id=' . $key2 . '" name="' . $value2 . '" title=" ' . $value2 . '">' . $value2 . '</a>';
        }
        echo implode(', ', $result_authors);
              echo '</td>
                    <td width=35%>дата на публикуване:<br> 
                    ' . $value['date_published'] . '
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

        <a href="logout.php" style="text-decoration: none;"><input type="button" value="Изход" /></a>

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