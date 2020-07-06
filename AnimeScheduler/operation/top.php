<?php
session_start();

require_once '../common/summary.php';

$pdo = new Dbget();
$pdo->connection();

$func = new Reuse();
$date = new Datetime();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>管理者ページ</title>
  <link href="../common/reset.css" rel="stylesheet" type="text/css">
  <link href="../common/common.css" rel="stylesheet" type="text/css">
  <link href="../operation/top.css" rel="stylesheet" type="text/css">
  <link rel="icon" type="image/png" href="../other/img/chidori.png">
  <link rel="apple-touch-icon" type="image/png" href="../other/img/chidori.png">
</head>

<body>
<div class="wrapper">
  <header>
    <div class="header">
      <div class="header__titleblock">
        <ul class="header__titleblock--list">
          <li class="header__titleblock--title"><h1>AnimeScheduler</h1></li>
          <li class="header__titleblock--user"><?php echo '<p>'.$_SESSION['name'].'</p>'; ?></li>
        </ul>
      </div>
    </div>
  </header>

  <div class="container">
    <div class="log">

      <!-- アクセスログ -->
      <article>
        <div class="log__access">
          <input class="access--input" type="checkbox" id="accessCheck">
          <label class="access--label" for="accessCheck">Last&nbsp;Access&nbsp;Log</label>
          <div class="access__info">
            <table class="access__info--table">
              <tr>
                <th>ユーザー名</th>
                <th>最終ログイン時間</th>
              </tr>
              <?php
                  $lastAccess = 'SELECT * FROM a_access';
                  $accessList = $pdo->selectOrder($lastAccess);
                  foreach ($accessList as $row){
                    echo '<tr>';
                    echo '<td data-label="ユーザー名">',$row['login_id'],'</td>';
                    echo '<td data-label="最終ログイン時間">',$row['login'],'</td>';
                    echo '</tr>';
                  }
                ?>
            </table>
          </div>
        </div>
      </article>

      <!-- 編集ログ -->
      <article>
        <div class="log__edit">
          <input class="edit--input" type="checkbox" id="editCheck">
          <label class="edit--label" for="editCheck">Last&nbsp;Edit&nbsp;Log</label>
          <div class="edit__info">
            <table class="edit__info--table">
              <tr>
                <th>更新ユーザー</th>
                <th>アニメタイトル</th>
                <th>コミットタイプ</th>
                <th>コミット日時</th>
              </tr>
              <?php
                $lastEdit = 'SELECT * FROM a_edit';
                $editList = $pdo->selectOrder($lastEdit);
                foreach ($editList as $row){
                  echo '<tr>';
                  echo '<td data-label="更新ユーザー">',$row['login_id'],'</td>';
                  echo '<td data-label="アニメタイトル">',$row['animation_title'],'</td>';
                  echo '<td data-label="コミットタイプ">',$row['type'],'</td>';
                  echo '<td data-label="コミット日時">',$row['commit_date'],'</td>';
                  echo '</tr>';
                }
            ?>
            </table>
          </div>
        </div>
      </article>

    </div>

    <!-- アニメ一覧 -->
    <article>
      <div class="container__anime">
        <div class="anime__manipulate">
          <div>
            <button onclick="location.href='../operation/add.php'">新規作成</button>
          </div>
          <div>
            <form method="post" action="">
              <select class="anime__manipulate--selectbox" name="choice" onchange="submit(this.form)">
                <?php

                  $optionList = $func->selectData();
                  $optionUnique = array_unique($optionList);

                  foreach($optionUnique as $val) {
                    $optionSplit = preg_split("/,/", $val);
                    echo '<option value="',$val,'"';

                      if (isset($_REQUEST['choice'])){

                        if($val === $_REQUEST['choice']){
                          echo 'selected';
                        };

                      } else {
                        
                        $year = $date->format('Y');
                        $month = $date->format('n');
                        $judgh = $func->animeCours($year, $month);

                        if($val === $judgh){
                          echo 'selected';
                        }

                      }
                      
                    echo '>';
                    echo $optionSplit[0],'年',$optionSplit[1],'アニメ</option>';
                  }

                ?>
              </select>
            </form>
          </div>
        </div>

        <div class="anime__broadcast">
          <form method="post" action="../operation/edit.php">
            <?php

                if(isset($_REQUEST['choice'])){
                  
                  $splitChoice = preg_split('/,/', $_REQUEST['choice']);
                  $searchSeason = 'SELECT '.
                                    'DISTINCT animation_id, '.
                                    'animation_title '.
                                   'FROM '.
                                    'a_schedule '.
                                   'WHERE '.
                                    'airdate LIKE \''.$splitChoice[0].'%\' '.
                                   'AND season = \''.$splitChoice[1].'\' COLLATE utf8mb4_general_ci';
                  
                  try {
                    
                    $seasonResult = $pdo->selectOrder($searchSeason);
                  
                  } catch (Exception $e) {
                    
                    echo "<p>".$e->getMessage()."</p>";
                    
                  }
                  
                  if (count($seasonResult) !== 0){

                    foreach ($seasonResult as $row){
                      echo '<div class="broadcast__listbrock">';
                      echo '<div><p>',$row['animation_title'],'</p></div>';
                      echo '<div class="broadcast__listbrock--button">';
                      echo '<button type="submit" name="edit" value="',$row['animation_id'],',',$splitChoice[0],',',$splitChoice[1],'">';
                      echo '<img src="../other/img/edit.png" alt="送信">';
                      echo '</button>';
                      echo '</div>';
                      echo '</div>';
                    }

                  } else {
                    echo '<div class="broadcast__error">';
                    echo '<p>データがありません。</p>';
                    echo '</div>';
                  }

                } else {

                  $year = $date->format('Y');
                  $month = $date->format('n');
                  $seasonSplit = preg_split('/,/', $func->animeCours($year, $month));
                  $seasonSql = 'SELECT animation_id, animation_title FROM a_schedule where airdate  like \''.$seasonSplit[0].'%\' and collate utf8_unicode_ci season = \''.$seasonSplit[1].'\' COLLATE utf8mb4_general_ci';
                  
                  try {
                  $todayResult = $pdo->selectOrder($seasonSql);
                  } catch (Exception $e) {
                    echo "<p>".$e->getMessage."</p>";
                  }

                  if (count($todayResult) !== 0){
                    
                    foreach ($todayResult as $row){
                      echo '<div class="broadcast__listbrock">';
                      echo '<div><p>',$row['animation_title'],'</p></div>';
                      echo '<div class="broadcast__listbrock--button"><button type="submit" name="edit" value="',$row['animation_id'],',',$seasonSplit[0],',',$seasonSplit[1],'"><img src="../other/img/edit.png" alt="送信"></button></div>';
                      echo '</div>';
                    }

                  } else {
                    echo '<div class="broadcast__error">';
                    echo '<p>データがありません。</p>';
                    echo '</div>';
                  }

                }

              ?>
          </form>
        </div>
      </div>
    </article>
  </div>

  <footer>
    <div class="footer">
      <ul>
        <li>"Where&nbsp;there&nbsp;is&nbsp;a&nbsp;will,&nbsp;there&nbsp;is&nbsp;a&nbsp;way."</li>
        <li>2020&nbsp;|&nbsp;AnimeScheduler</li>
      </ul>
    </div>
  </footer>

</div>
</body>

</html>