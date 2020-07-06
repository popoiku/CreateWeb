<?php
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
  <title>TVアニメ放送スケジュール一覧&nbsp;|&nbsp;AnimeScheduler</title>
  <link href="../common/reset.css" rel="stylesheet" type="text/css">
  <link href="../common/common.css" rel="stylesheet" type="text/css">
  <link href="../anyone/index.css" rel="stylesheet" type="text/css">
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
          <li class="header__titleblock--user"><a href="../gate/guardian.php">log&nbsp;in</a></li>
        </ul>
      </div>
    </div>
  </header>

  <div class="container">
    <article>
      
      <!-- navigation -->
      <div class="container__info">
        <div class="info__block">
          <form method="post" action="">
            <label class="info__block--label" for="selectAnime">TVアニメ放送時期</label>
            <select class="info__block--select" name="getSeason" onchange="submit(this.form)" id="selectAnime">
              <?php
                // 年と季節でソート
                $selectOption = $func->selectData();
                $uniqueSelect = array_unique($selectOption);

                foreach($uniqueSelect as $val) {
                  $giveOption = preg_split("/,/", $val);
                  echo '<option value="',$val,'"';

                    if (isset($_REQUEST['getSeason'])){

                      if($val === $_REQUEST['getSeason']){
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
                  echo $giveOption[0],'年',$giveOption[1],'アニメ</option>';
                }

              ?>
            </select>
          </form>
        </div>
        <div class="twitter">
          <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-show-count="false">Tweet</a>
          <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>          
        </div>
      </div>
      
      <!-- anime infomation -->
      <div class="animeList">
        <?php

          if (isset($_POST['getSeason'])){
            
            // セレクトボックスで選択した"年"と"季節"を生成
            $seasonOrigin = preg_split('/,/', $_POST['getSeason']);
            $seasonYear = $seasonOrigin[0];
            $seasonSplit = $seasonOrigin[1];


          } else {
            
          // 現在の"年"と"季節"を生成
            $infoYear = $date->format('Y');
            $infoMonth = $date->format('n');
            $infoSeason = preg_split('/,/', $func->animeCours($infoYear, $infoMonth));
  
            $seasonYear = $infoYear;
            $seasonSplit = $infoSeason[1];
            
          }

          // クエリ ⇒ ストアド  要検討
          $titleSQL = 'SELECT '.
                        'DISTINCT animation_id, '.
                        'animation_title, '.
                        'official_link, '.
                        'MIN(airdate) AS min_airdate '.
                      'FROM '.
                        'a_schedule '.
                      'WHERE '.
                        'airdate LIKE "'.$seasonYear.'%" '.
                      'AND '.
                        'season = "'.$seasonSplit.'" COLLATE utf8mb4_general_ci '.
                      'GROUP BY '.
                        'animation_id '.
                      'ORDER BY '.
                        'min_airdate ASC';
          
          $resultTitle = $pdo->selectOrder($titleSQL);
          
          // データ存在確認
          if (count($resultTitle) === 0){
            echo '<div class="notDate">';
            echo '<img src="../other/img/unimplemented.png">';
            echo '<p>指定した期間の放送スケジュールデータは未追加のため表示されません。</p>';
            echo '<p>少々お待ちください。</p>';
            echo '</div>';
          }
          
          try {
            
            for ($i = 0; $i < count($resultTitle); $i++){
              $animeId = $resultTitle[$i]['animation_id'];
              
              // クエリ ⇒ ストアド  要検討
              $sheduleSQL = 'SELECT '.
                              'broadcaster_name, '.
                              'DATE_FORMAT(airdate, \'%m月%d日\') AS airdate, '.
                              'week, '.
                              'DATE_FORMAT(airtime, \'%H:%i\') AS airtime, '.
                              'DATE_FORMAT(first_airtime, \'%H:%i\') AS first_airtime '.
                            'FROM '.
                              'a_schedule '.
                            'WHERE '.
                              'airdate like "'.$seasonYear.'%" '.
                            'AND '.
                              'animation_id = '.$animeId.' '.
                            'AND '.
                              'season = "'.$seasonSplit.'" COLLATE utf8mb4_general_ci '.
                            'ORDER BY '.
                              'airdate ASC, '.
                              'airtime ASC';
              
              $shedule = $pdo->selectOrder($sheduleSQL);
              
              echo '<div class="animeList__info">';
              echo '<div class="info__official" >';
              echo '<button class="info__official--button" onclick="window.open(\'',$resultTitle[$i]['official_link'],'\')">';
              echo '<img src="../other/img/link.png" class="info__official--img"><span class="info__official--span">',$resultTitle[$i]['animation_title'],'</span>';
              echo '</button>';
              echo '</div>';
              echo '<div class="info__broadcast">';
              
              echo '<table class="info__broadcast--table">';
              echo '<tr><th>放送局</th><th>放送日</th><th>放送時間</th><th>放送時間（初回）</th></tr>';
              
              
              for ($j = 0; $j < count($shedule); $j++){
                echo '<tr>';
                echo '<td data-label="放送局">'.$shedule[$j]['broadcaster_name'].'</td>';
                echo '<td data-label="放送日">'.$shedule[$j]['airdate'].'('.$shedule[$j]['week'].')</td>';
                echo '<td data-label="放送時間">'.$shedule[$j]['airtime'].'</td>';
                echo '<td data-label="放送時間（初回）">'.$shedule[$j]['first_airtime'].'</td>';
                echo '</tr>';
              }
            
              echo '</table>';
              echo '</div>';
              echo '</div>';
            }
          
          } catch (Exception $e) {
            
            echo '<div class="error">';
            echo '<p>エラーが発生しました。</p>';
            echo '<p>現在、修復を行っているため時間をおいて再度アクセスしてください。</p>';
            echo '<p>'.$e->getMessage().'</p>';
            echo '</div>';

          }
          
        ?>
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