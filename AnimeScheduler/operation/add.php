<?php
session_start([
    'gc_maxlifetime' => 86400,  
  ]);

require_once '../common/summary.php';
$pdo = new Dbget();
$pdo->connection();

if (isset($_SESSION['name'])){
} else {
  header('Location: ../gate/guardian.php');
  exit;
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>新規追加</title>
  <link href="../common/reset.css" rel="stylesheet" type="text/css">
  <link href="../common/common.css" rel="stylesheet" type="text/css">
  <link href="../operation/add.css" rel="stylesheet" type="text/css">
  <script type="text/javascript" src="../operation/add.js"></script>
  <link rel="icon" type="image/png" href="../other/img/chidori.png">
  <link rel="apple-touch-icon" type="image/png" href="../other/img/chidori.png">

</head>

<body>
<div class="wrapper">
  <article>
    <header>
      <div class="header">
        <div class="header__titleblock">
          <ul class="header__titleblock--list">
            <li class="header__titleblock--title">
              <h1>AnimeScheduler</h1>
            </li>
            <li class="header__titleblock--user"><?php echo '<p>'.$_SESSION['name'].'</p>'; ?></li>
          </ul>
        </div>
      </div>
    </header>
    <div class="container">
      <form action="" method="post" name="addAll" onsubmit="return formCheck()" spellcheck="false">
        
        <div class="addanime">
          <!-- 番組タイトル -->
          <div class="addanime__titleblock">
            <label class="addanime__titleblock--label" for="" >番組タイトル</label>
            <input class="addanime__titleblock--input" type="text" name="animetitle" >
          </div>
          <!-- 公式HPリンク -->
          <div class="addanime__titleblock">
            <label class="addanime__titleblock--label" for="" >公式HPリンク</label>
            <input class="addanime__titleblock--input" type="text" name="animelink">
          </div>
        </div>
        
        <div class="addschedule">
          <table class="addtable">
            <thead>
              <tr>
                <th>放送局</th>
                <th>放送日</th>
                <th>放送時間</th>
                <th>放送時間（初回）</th>
              </tr>
            </thead>
            <tbody id="tablebody">
              <tr id="iN0">
                <td data-label="放送局">
                  <input type="text" name="broadcast0">
                </td>
                <td data-label="放送日">
                  <select name="y_date0" id="year0"></select><!--
                  --><select name="m_date0" id="month0"></select><!--
                  --><select name="d_date0" id="day0"></select>
                </td>
                <td data-label="放送時間">
                  <select name="h_time0" id="hour0"></select><!--
                  --><select name="m_time0" id="minute0"></select>
                </td>
                <td data-label="放送時間（初回）">
                  <select name="h_firsttime0" id="f_hour0"></select><!--
                  --><select name="m_firsttime0" id="f_minute0"></select>
                </td>
              </tr>
            </tbody>
          </table>
          </div>
          
          <div class="addbutton">
            <div class="addbutton__appearance ">
              <button class="addbutton__appearance--button" type="button" onclick="return addInput()">＋</button>
            </div>
            <div class="addbutton__appearance ">
              <button class="addbutton__appearance--button" type="button" onclick="return deleteInput()">－</button>
            </div>
          </div>
            
          <div class="addsubmit">
            <button class="addsubmit--button" type="submit" name="transmission">送信</button>
          </div>
          
          <?php
            
            try {
              
              if(isset($_POST['transmission'])){
                
                // 動的追加項目から受け取った値を配列へ
                $sendArray = array_slice($_POST, 2);
                unset($sendArray['transmission']);
                
                $valueSorting = array_values($sendArray);
                
                $startNumber = 0;
                $repetNumber = 8;
                $maltiArray = [];
                
                for ($i = 0; $i < count($valueSorting) / 8; $i++){
                  $individualArray = [];

                  for ($j = $startNumber; $j < $repetNumber; $j++){
                    array_push($individualArray, $valueSorting[$j]);
                    
                  }
                  
                  array_push($maltiArray, $individualArray);
                  
                  $startNumber += 8;
                  $repetNumber += 8;
                }
                
                // titleテーブルへ登録
                $titleShaping = trim($_POST['animetitle']);
                $linkShaping = trim($_POST['animelink']);
                $titleSearch = $pdo->titleSelect($titleShaping);

                if($titleSearch){
                  $titleId = $titleSearch['id'];
                  
                  $pdo->editHistry($_SESSION['name'], $titleShaping, 4);

                } else { 
                  $unregistered = $pdo->unregisteredAnime($_POST['animetitle'], $_POST['animelink']);
                  
                  if($unregistered){
                    $titleSearch = $pdo->titleSelect($titleShaping);
                    $titleId = $titleSearch['id'];

                  $pdo->editHistry($_SESSION['name'], $titleShaping, 1);
                  }
                  
                }
                
                // sheduleテーブルへ登録
                for($i = 0; $i < count($maltiArray); $i++){
                  $broadcastTrim = trim($maltiArray[$i][0]);
                  $broadcastSearch = $pdo->broadcastConfirm($broadcastTrim);
    
                  if ($broadcastSearch){
                    $broadcaster = $broadcastSearch['id'];
                    
                  } else {
                    $pdo->addBroadcast($broadcastTrim);
                    $addSearch = $pdo->broadcastConfirm($broadcastTrim);
                    $broadcaster = $addSearch['id'];
                    
                  }
                
                  $dateBroadcast = $maltiArray[$i][1]."-".$maltiArray[$i][2]."-".$maltiArray[$i][3];
                  $timeBroadcast = $maltiArray[$i][4].":".$maltiArray[$i][5];
                  $firsttimeBroadcast = $maltiArray[$i][6].":".$maltiArray[$i][7];
                  
                  $pdo->addShedule($titleId, $broadcaster, $dateBroadcast, $timeBroadcast, $firsttimeBroadcast);
                  
                }
                
                echo '<div class="complete"><p>正常に送信しました。</p></div>';
              }
              
            } catch(PDOException $e) {
                
                echo '<div class="error">';
                echo '<p>エラーが発生しました。開発者へお問い合わせください。';
                echo '<p>'.$e->getMessage().'</p>';
                echo '</div>';
            }
              
          ?>

      </form>
    </div>

    <footer>
      <div class="footer">
        <ul>
          <li>"Where&nbsp;there&nbsp;is&nbsp;a&nbsp;will,&nbsp;there&nbsp;is&nbsp;a&nbsp;way."</li>
          <li>2020&nbsp;|&nbsp;AnimeScheduler</li>
        </ul>
      </div>
    </footer>
  </article>
</div>
</body>

</html>