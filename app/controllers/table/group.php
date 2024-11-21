<?php

    if (!mysqli_exist_token($_SESSION['token'])) {
        return_user('../logout');
    }

    if (array_key_exists('delete-row', $_POST)) {
        $stmt = $mysqli->prepare("DELETE FROM `groups` WHERE `groups`.`id` = ?");
        $stmt->bind_param("i", $_POST['delete-row']);
        $stmt->execute();
        $stmt->close();
    }

    if (array_key_exists('gname', $_POST)) {

        if (array_key_exists('level', $_POST)) {
            $level = $_POST["level"];
        } else {
            $level = 0;
        }

        if (array_key_exists('kname', $_POST)) {
            $kname = $_POST["kname"];
        } else {
            $kname = 0;
        }

        if (array_key_exists('teacher', $_POST)) {
            $teacher = $_POST["teacher"];
        } else {
            $teacher = 0;
        }

        $stmt = $mysqli->prepare("INSERT INTO `groups` (`name`, `level`, `to_kvant`, `to_teachers`) VALUES (?, ?, ?, ?);");
        $stmt->bind_param("siii", $_POST["gname"], $level, $kname, $teacher);
        $stmt->execute();
        $stmt->close();
    }

    $kvants = $mysqli->query("SELECT `id`, `name` FROM `kvants`");

    $teachers = $mysqli->query("SELECT `id`, `lastname`, `firstname`, `patname` FROM `teachers`");

    $groups = $mysqli->query("SELECT  `groups`.`id`, `groups`.`name` as gname, `kvants`.`name` as kname, `groups`.`level`, `teachers`.`lastname`, `teachers`.`firstname`, `teachers`.`patname` FROM `groups` JOIN `kvants` ON `groups`.`to_kvant` = `kvants`.`id` JOIN `teachers` ON `groups`.`to_teachers` = `teachers`.`id`");

    if (array_key_exists("search", $_GET)) {

        $sq = ("SELECT `groups`.`id`, `groups`.`name` as gname, `kvants`.`name` as kname, `groups`.`level`, `teachers`.`lastname`, `teachers`.`firstname`, `teachers`.`patname` FROM `groups` JOIN `kvants` ON `groups`.`to_kvant` = `kvants`.`id` JOIN `teachers` ON `groups`.`to_teachers` = `teachers`.`id`");
        $sq_edit = 0;

        $_query = $_GET['search'];

        if (array_key_exists("gname-ch", $_GET)) {
            if ($sq_edit == 0) {
                $sq = $sq."WHERE";
            }  else {
                $sq = $sq."OR";
            }

            $sq = $sq."`groups`.`name` LIKE '$_query'";
            $sq_edit += 1;

            }

        if (array_key_exists("kname-ch", $_GET)) {
            if ($sq_edit == 0) {
                $sq = $sq."WHERE";
            }  else {
                $sq = $sq."OR";
            }

            $sq = $sq."`kvants`.`name` LIKE '$_query'";
            $sq_edit += 1;

        }

        if (array_key_exists("level-ch", $_GET)) {
            if ($sq_edit == 0) {
                $sq = $sq."WHERE";
            }  else {
                $sq = $sq."OR";
            }

            $sq = $sq."`groups`.`level` LIKE '$_query'";
            $sq_edit += 1;

        }

        if (array_key_exists("teacher-ch", $_GET)) {
            if ($sq_edit == 0) {
                $sq = $sq."WHERE";
            }  else {
                $sq = $sq."OR";
            }

            $sq = $sq."`teachers`.`lastname` LIKE '$_query' OR `teachers`.`firstname` LIKE '$_query' OR `teachers`.`patname` LIKE '$_query'";
            $sq_edit += 1;

        }

        if ($sq_edit != 0) {
            $groups = $mysqli->query($sq);
        } else {
            if (!empty($_query)) {
                $groups = $mysqli->query("SELECT `groups`.`id`, `groups`.`name` as gname, `kvants`.`name` as kname, `groups`.`level`, `teachers`.`lastname`, `teachers`.`firstname`, `teachers`.`patname` FROM `groups` JOIN `kvants` ON `groups`.`to_kvant` = `kvants`.`id` JOIN `teachers` ON `groups`.`to_teachers` = `teachers`.`id` WHERE `groups`.`name` LIKE '$_query' OR `kvants`.`name` LIKE '$_query' OR `groups`.`level` LIKE '$_query' OR `teachers`.`lastname` LIKE '$_query' OR `teachers`.`firstname` LIKE '$_query' OR `teachers`.`patname` LIKE '$_query'");
        }
        
    }

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кванториум</title>
</head>
<body>
    <header>
        <p class="header-title">Кванториум</a>
        <a href="../logout"><input type="submit" value="Выход" class="header-exit"></a>
    </header>

    <main>

    <form action="" method="get">
        <input type="text" name="search" id="search"><br><br>
        <label for="gname-ch" id="gnamech"><input type="checkbox" name="gname-ch" id="gname-ch"> - Имя группы</label><br>
        <label for="kname-ch" id="knamech"><input type="checkbox" name="kname-ch" id="kname-ch"> - Объединение</label><br>
        <label for="level-ch" id="lvlch"><input type="checkbox" name="level-ch" id="level-ch"> - Уровень (Индекс)</label><br>
        <label for="teacher-ch" id="teacherch"><input type="checkbox" name="teacher-ch" id="teacher-ch"> - ФИО Преподавателя</label><br><br>
        <input type="submit" value="Найти" class="searchbutton" id="searchbutton">
    </form>

        <div> 
            <p id="name">ГБУ ДО ВО «ЦИКДиМ «Кванториум»</p>
            <p id="town">Город Россошь</p>
            <p id="chertochka">-</p>
            <p id="region">Воронежская область</p>
        </div>
        <div>
        <p id="child"><a href="group">Группы</a>/<a href="student">Ученики</a></p>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>№</th>
                    <th>Номер группы</th>
                    <th>Объединение</th>
                    <th>Уровень обучения</th>
                    <th>Преподаватель</th>
                </tr>
            </thead>
            <tbody>
                <form action="" method="post">
                    <tr>
                    <td><input type="submit" value="+" id="save-add2"></td>
                    <td><input type="text" name="gname" placeholder="Название группы" class="input-add"></td>

                    <td><select name="kname" class="select-add">
                        <option value="None" selected disabled>Объединения</option>
                        <?php
                            foreach ($kvants as $kvant) {
                                echo("<option value=".$kvant["id"].">".$kvant["name"]."</option>");
                            }
                        ?>
                    </select></td>

                    <td><select name="level" class="select-add">
                        <option value="None" selected disabled>Уровень обучения</option>
                        <option value="0">0 (Вводный)</option>
                        <option value="1">1 (Углублённый)</option>
                        <option value="2">2 (Проектный)</option>
                    </select></td>

                    <td><select name="teacher" class="select-add">
                        <option value="None" selected disabled>Учитель</option>
                        <?php
                            foreach ($teachers as $teacher) {
                                echo("<option value=".$teacher["id"].">".$teacher['lastname']." ".substr($teacher['firstname'], 0, 2).". ".substr($teacher['patname'], 0, 2).".</option>");
                            }
                        ?>
                    </select></td>
                    
                    <!-- <input type="submit" value="Создать" id="save-add"> -->
                </tr>
                </form>

                <?php
                    $groups_row = 1;
                    foreach ($groups as $group) {
                        if ($group['id'] != 0) {
                            if ($group['level'] == 0) {
                                $group['level'] = $group['level'].' (Вводный)';
                            } elseif ($group['level'] == 1) {
                                $group['level'] = $group['level'].' (Углублённый)';
                            } elseif ($group['level'] == 2) {
                                $group['level'] = $group['level'].' (Проектный)';
                            }

                            echo '
                                    <tr>
                                        <td><form action="" method="post" onsubmit="return delete_row('.$groups_row.')"><input type="number" name="delete-row" value="'.$group['id'].'" hidden><input type="submit" value="'.$groups_row.'" class="delete-row"></form></td>
                                        <td>'.$group['gname'].'</td>
                                        <td>'.$group['kname'].'</td>
                                        <td>'.$group['level'].'</td>
                                        <td>'.$group['lastname'].' '.substr($group['firstname'], 0, 2).'. '.substr($group['patname'], 0, 2).'.'.'</td>
                                    </tr>';
                            $groups_row += 1;
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
    btn = document.getElementsByClassName("delete-row");

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