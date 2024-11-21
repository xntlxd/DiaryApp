<?php

    if (!mysqli_exist_token($_SESSION['token'])) {
        return_user('../logout');
    }

    if (array_key_exists('delete-row', $_POST)) {
        $stmt = $mysqli->prepare("DELETE FROM `students` WHERE `students`.`id` = ?");
        $stmt->bind_param("i", $_POST['delete-row']);
        $stmt->execute();
        $stmt->close();
    }

    if (array_key_exists('lastname', $_POST)) {
        if (array_key_exists('lastname', $_POST)) {
            $lastname = $_POST["lastname"];
        } else {
            $lastname = "Неопознанный";
        }
        if (array_key_exists('firstname', $_POST)) {
            $firstname = $_POST["firstname"];
        } else {
            $firstname = "Студент";
        }
        if (array_key_exists('patname', $_POST)) {
            $patname = $_POST["patname"];
        } else {
            $patname = "Студент";
        }
        if (array_key_exists('birthday', $_POST)) {
            $birthday = $_POST["birthday"];
        } else {
            $birthday = "0000-00-00";
        }
        if (array_key_exists('sex', $_POST)) {
            $sex = $_POST["sex"];
        } else {
            $sex = "None";
        }
        if (array_key_exists('group', $_POST)) {
            $group = $_POST["group"];
        } else {
            $group = "0";
        }
        if (array_key_exists('school', $_POST)) {
            $school = $_POST["school"];
        } else {
            $school = "0";
        }

        $pfdo = random_int(100000, 999999);
        $id_parents = 0;

        $stmt = $mysqli->prepare("INSERT INTO `students` (`lastname`, `firstname`, `patname`, `birthday`, `sex`,`to_groups`, `pfdo`, `to_schools`, `to_parents`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssisii", $lastname, $firstname, $patname, $birthday, $sex, $group, $pfdo , $school, $id_parents);
        $stmt->execute();
        $stmt->close();
    }

    $people = $mysqli->query("SELECT `s`.`id`, `s`.`lastname`, `s`.`firstname`, `s`.`patname`, `s`.`birthday`, `s`.`sex`, `g`.`name` as gname, `k`.`name` as kname, `s`.`pfdo`, `sc`.`name`as school ,`p`.`lastname_mother`, `p`.`firstname_mother`, `p`.`patname_mother`, `p`.`number_mother` FROM `students` as s JOIN `groups` as g ON `s`.`to_groups` = `g`.`id` JOIN `kvants` as k ON `g`.`to_kvant` = `k`.`id` JOIN `schools` as sc ON `s`.`to_schools` = `sc`.`id` JOIN `parents` as p ON `s`.`to_parents` = `p`.`id`;");

    $groups = $mysqli->query("SELECT `g`.`id`, `g`.`name` as gname, `k`.`name` as kname FROM `groups` as g JOIN `kvants` as k ON `g`.`to_kvant` = `k`.`id`;");

    $schools = $mysqli->query("SELECT * FROM `schools`");

    if (array_key_exists("search", $_GET)) {

        $sq = ("SELECT `s`.`id`, `s`.`lastname`, `s`.`firstname`, `s`.`patname`, `s`.`birthday`, `s`.`sex`, `g`.`name` as gname, `k`.`name` as kname, `s`.`pfdo`, `sc`.`name`as school ,`p`.`lastname_mother`, `p`.`firstname_mother`, `p`.`patname_mother`, `p`.`number_mother` FROM `students` as s JOIN `groups` as g ON `s`.`to_groups` = `g`.`id` JOIN `kvants` as k ON `g`.`to_kvant` = `k`.`id` JOIN `schools` as sc ON `s`.`to_schools` = `sc`.`id` JOIN `parents` as p ON `s`.`to_parents` = `p`.`id`");
        $sq_edit = 0;

        $_query = $_GET['search'];

        if (array_key_exists("lastname-ch", $_GET)) {
            if ($sq_edit == 0) {
                $sq = $sq."WHERE ";
            }  else {
                $sq = $sq."OR ";
            }

            $sq = $sq."`s`.`lastname` LIKE '$_query'";
            $sq_edit += 1;

        }

        if (array_key_exists("firstname-ch", $_GET)) {
            if ($sq_edit == 0) {
                $sq = $sq."WHERE ";
            }  else {
                $sq = $sq."OR ";
            }

            $sq = $sq."`s`.`firstname` LIKE '$_query'";
            $sq_edit += 1;

        }

        if (array_key_exists("patname-ch", $_GET)) {
            if ($sq_edit == 0) {
                $sq = $sq."WHERE ";
            }  else {
                $sq = $sq."OR ";
            }

            $sq = $sq."`s`.`patname` LIKE '$_query'";
            $sq_edit += 1;

        }

        if (array_key_exists("birthday-ch", $_GET)) {
            if ($sq_edit == 0) {
                $sq = $sq."WHERE ";
            }  else {
                $sq = $sq."OR ";
            }

            $sq = $sq."`s`.`birthday` LIKE '$_query'";
            $sq_edit += 1;

        }

        if (array_key_exists("sex-ch", $_GET)) {
            if ($sq_edit == 0) {
                $sq = $sq."WHERE ";
            }  else {
                $sq = $sq."OR ";
            }

            $sq = $sq."`s`.`sex` LIKE '$_query'";
            $sq_edit += 1;

        }

        if (array_key_exists("group-ch", $_GET)) {
            if ($sq_edit == 0) {
                $sq = $sq."WHERE ";
            }  else {
                $sq = $sq."OR ";
            }

            $sq = $sq."`g`.`name` LIKE '$_query'";
            $sq_edit += 1;

        }

        if (array_key_exists("kvant-ch", $_GET)) {
            if ($sq_edit == 0) {
                $sq = $sq."WHERE ";
            }  else {
                $sq = $sq."OR ";
            }

            $sq = $sq."`kvant`.`name` LIKE '$_query'";
            $sq_edit += 1;

        }

        if (array_key_exists("pfdo-ch", $_GET)) {
            if ($sq_edit == 0) {
                $sq = $sq."WHERE ";
            }  else {
                $sq = $sq."OR ";
            }

            $sq = $sq."`s`.`pfdo` LIKE '$_query'";
            $sq_edit += 1;

        }

        if (array_key_exists("school-ch", $_GET)) {
            if ($sq_edit == 0) {
                $sq = $sq."WHERE ";
            }  else {
                $sq = $sq."OR ";
            }

            $sq = $sq."`sc`.`name` LIKE '$_query'";
            $sq_edit += 1;

        }

        if (array_key_exists("parents-ch", $_GET)) {
            if ($sq_edit == 0) {
                $sq = $sq."WHERE ";
            }  else {
                $sq = $sq."OR ";
            }

            $sq = $sq."`p`.`lastname_mother` LIKE '$_query' OR `p`.`firstname_mother` LIKE '$_query' OR `p`.`patname_mother` LIKE '$_query'";
            $sq_edit += 1;

        }

        if (array_key_exists("pnumber-ch", $_GET)) {
            if ($sq_edit == 0) {
                $sq = $sq."WHERE ";
            }  else {
                $sq = $sq."OR ";
            }

            $sq = $sq."`p`.`number_mother` LIKE '$_query'";
            $sq_edit += 1;

        }

        if ($sq_edit != 0) {
            $people = $mysqli->query($sq);
        } else {
            if (!empty($_query)) {
                $people = $mysqli->query("SELECT `s`.`id`, `s`.`lastname`, `s`.`firstname`, `s`.`patname`, `s`.`birthday`, `s`.`sex`, `g`.`name` as gname, `k`.`name` as kname, `s`.`pfdo`, `sc`.`name`as school ,`p`.`lastname_mother`, `p`.`firstname_mother`, `p`.`patname_mother`, `p`.`number_mother` FROM `students` as s JOIN `groups` as g ON `s`.`to_groups` = `g`.`id` JOIN `kvants` as k ON `g`.`to_kvant` = `k`.`id` JOIN `schools` as sc ON `s`.`to_schools` = `sc`.`id` JOIN `parents` as p ON `s`.`to_parents` = `p`.`id`WHERE  `s`.`lastname` LIKE '$_query' OR  `s`.`firstname` LIKE '$_query' OR  `s`.`patname` LIKE '$_query' OR  `s`.`birthday` LIKE '$_query' OR  `g`.`name` LIKE '$_query' OR  `k`.`name` LIKE '$_query' OR  `s`.`pfdo` LIKE '$_query' OR  `sc`.`name` LIKE '$_query' OR  `p`.`lastname_mother` LIKE '$_query' OR  `p`.`firstname_mother` LIKE '$_query' OR  `p`.`patname_mother` LIKE '$_query' OR  `p`.`number_mother` LIKE '$_query'");
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кванториум</title>
</head>
<body>
    <header>
        <p class="header-title">Кванториум</a>
        <a href="../logout"><input type="submit" value="Выход" class="header-exit"></a>
    </header>

    <form action="" method="get">
        <input type="text" name="search" id="search2"><br><br>
        <label for="lastname-ch" id="lastch"><input type="checkbox" name="lastname-ch" id="lastname-ch"> - Фамилия</label><br>
        <label for="firstname-ch" id="firstch"><input type="checkbox" name="firstname-ch" id="firstname-ch"> - Имя</label><br>
        <label for="patname-ch" id="patch"><input type="checkbox" name="patname-ch" id="patname-ch"> - Отчество</label><br>
        <label for="birthday-ch" id="birthch"><input type="checkbox" name="birthday-ch" id="birthday-ch"> - День рождения</label><br>
        <label for="sex-ch" id="sexch"><input type="checkbox" name="sex-ch" id="sex-ch"> - Пол</label><br>
        <label for="group-ch" id="groupch"><input type="checkbox" name="group-ch" id="group-ch"> - Группа</label><br>
        <label for="kvant-ch" id="kvantch"><input type="checkbox" name="kvant-ch" id="kvant-ch"> - Объединение</label><br>
        <label for="pfdo-ch" id="pfdoch"><input type="checkbox" name="pfdo-ch" id="pfdo-ch"> - ПФДО</label><br>
        <label for="school-ch" id="schoolch"><input type="checkbox" name="school-ch" id="school-ch"> - Школа</label><br>
        <label for="parents-ch" id="parch"><input type="checkbox" name="parents-ch" id="parents-ch"> - Родители</label><br>
        <label for="pnumber-ch" id="pnumberch"><input type="checkbox" name="pnumber-ch" id="pnumber-ch"> - Родительский Телефон</label><br><br>
        <input type="submit" value="Найти" class="searchbutton" id="seachbutton2">
    </form>

    <main>
        <div>
            <p id="name">ГБУ ДО ВО «ЦИКДиМ «Кванториум»</p>
            <p id="town">Город Россошь</p>
            <p id="chertochka">-</p>
            <p id="region">Воронежская область</p>
        </div>
        <div>
        <p id="child"><a href="group">Группы</a>/<a href="student">Ученики</a></p>
        </div>
        <table class="table" id="fixtable">
            <thead>
                <tr>
                    <th>№</th>
                    <th>Фамилия</th>
                    <th>Имя</th>
                    <th>Отчество</th>
                    <th>Дата рождения</th>
                    <th>Пол</th>
                    <th>Номер группы</th>
                    <th>Объединение</th>
                    <th>Личный код</th>
                    <th>Наименование ОУ</th>
                    <th>ФИО родителей</th>
                    <th>Контакт родителя</th>
                </tr>
            </thead>
            <tbody>
                <form action="" method="post">
                    <tr>
                        <td><input type="submit" value="+" id="save-add"></td>
                        <td><input type="text" name="lastname" placeholder="Фамилия" class="select-add"></td>
                        <td><input type="text" name="firstname" placeholder="Имя" class="select-add"></td>
                        <td><input type="text" name="patname" placeholder="Отчество" class="select-add"></td>
                        <td><input type="date" name="birthday" placeholder="Дата" class="select-add"></td>
                        <td><select name="sex" class="select-add">
                            <option value="None" selected disabled>Пол</option>
                            <option value="male">Муж.</option>
                            <option value="female">Жен.</option>
                        </select></td>
                        <td><select name="group" class="select-add">
                            <option value="none" disabled selected>Группа</option>
                                <?php
                                    foreach ($groups as $group) {
                                        echo("<option value=".$group['id'].">".$group['gname']." | ".$group['kname']."</option>");
                                    }
                                ?>
                            </select></td>
                        <td>Объединение</td>
                        <td><input type="text" name="pfdo" placeholder="ПФДО" class="select-add"></td>
                        <td><select name="school" class="select-add">
                            <option value="none" disabled selected>ОУ</option>
                                <?php
                                    foreach ($schools as $school) {
                                        echo("<option value=".$school['id'].">".$school['name']."</option>");
                                    }
                                ?>
                            </select></td>
                        <td><input type="text" name="" placeholder="Родители" class="select-add" disabled></td>
                        <td><input type="number" name="" placeholder="Контакт с Род." class="select-add"></td>
                    </tr>
                    <!-- <input type="submit" value="Сохранить" id="save-add"> -->
                </form>
                <?php
                    $number_row = 1;
                    foreach ($people as $person) {
                        if ($person['id'] != 0) {
                            if ($person['sex'] == 'male') {
                                $person['sex'] = 'Муж.'; 
                            } elseif ($person['sex'] == 'female') {
                                $person['sex'] = 'Жен.'; 
                            } else {
                                $person['sex'] = 'Нео.';
                            }

                            echo '<tr>
                                    <td><form method="post" onsubmit="return delete_row('.$number_row.')"><input type="number" name="delete-row" value="'.$person['id'].'" hidden><input type="submit" value="'.$number_row.'" class="delete-row-p"></form></td>
                                    <td>'.$person['lastname'].'</td>
                                    <td>'.$person['firstname'].'</td>
                                    <td>'.$person['patname'].'</td>
                                    <td>'.$person['birthday'].'</td>
                                    <td>'.$person['sex'].'</td>
                                    <td>'.$person['gname'].'</td>
                                    <td>'.$person['kname'].'</td>
                                    <td>'.$person['pfdo'].'</td>
                                    <td>'.$person['school'].'</td>
                                    <td>'.$person['lastname_mother'].' '.substr($person['firstname_mother'], 0, 6).'. '.substr($person['patname_mother'], 0, 6).'.</td>
                                    <td>'.$person['number_mother'].'</td>
                                </tr>';
                            $number_row += 1;
                        }
                    }
                ?>
            </tbody>
        </table>
    </main>

</body>
</html>

<script>
    let varclick = 0,
    btn = document.getElementsByClassName("delete-row-p");

    function delete_row(id) {
        id -= 1;
        if (varclick != 1) {
            btn[id].style.backgroundImage = 'url(../assets/svg/check.svg)';
            console.log(varclick);
            varclick += 1;
            return false;
        } else if (varclick == 1) {
            btn[id].style.backgroundImage = 'url(../assets/svg/check.svg)';
            varclick = 0;
            return true;
        }
    }
</script>