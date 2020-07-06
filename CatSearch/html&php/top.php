<?php
require_once '../04_php/summary.php';
$pdo = new Dbget();
$pdo->con();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>CatSearch</title>
  <link href="../02_css/reset.css" rel="stylesheet" type="text/css">
  <link href="../02_css/common.css" rel="stylesheet" type="text/css">
  <link href="../02_css/top.css" rel="stylesheet" type="text/css">
  <link href="../00_img/nikukyuu_kai.png" rel="icon" type="image/png">
  <link href="../00_img/nikukyuu_kai.png" rel="apple-touch-icon" type="image/png">
  <script type="text/javascript" src="../03_javascript/top.js"></script>
  <script src="../anime-master/lib/anime.min.js"></script>
</head>

<body class="wrapper">
  <article>
    <div class="back" id="return_top">
      <img src="../00_img/toppage.png" alt="Top">
    </div>
    <!-- ヘッダー -->
    <div class="header">
      <header>
        <nav>
          <ul class="header__list">
            <li><img src="../00_img/6037.png" width="35" height="35" alt="LookUpCat"></li>
            <li><a href="../01_html/about.html">about</a></li>
          </ul>
        </nav>
      </header>
    </div>
    <!-- コンテナ― -->
    <div class="container">
      <!-- 検索 -->
      <form autocomplete="off" method="post" action="" id="f1">
        <div class="searchblock">
          <ul class="searchblock__list">
            <li>
              <h2>CatSearch</h2>
            </li>
            <li>
              <div class="searchblock__inputfield">
                <input type="text" name="search" id="search_field" class="searchblock__inputfield--intext">
                <input type="image" src="../00_img/search.png" alt="送信" id="submit-image"
                  class="searchblock__inputfield--inimg">
              </div>
            </li>
          </ul>
        </div>
      </form>
      <!-- カテゴリー -->
      <form name="f2" method="post" action="../01_html/details.php">
        <div class="catalog">
          <?php
            if (isset($_REQUEST['search'])){

              $result = $pdo->name_search($_REQUEST['search']);

              if (count($result) !== 0){

              foreach ($result as $val){
                echo '<div class="item">';
                echo '<button type="submit" name="catinfo" value="',$val['id'],'">';
                echo '<img src="../00_img/cat_all_types/',$val['img'],'">';
                echo '<p>',$val['brand'],'</p>';
                echo '</button></div>';
              }

              } else {
                echo '<div class="nohit">';
                echo '<p>該当する品種はありません。</p>';
                echo '</div>';
                
              }

            } else {

              $resultall = $pdo->all_search();

              foreach ($resultall as $val){
                echo '<div class="item">';
                echo '<button type="submit" name="catinfo" value="',$val['id'],'">';
                echo '<img src="../00_img/cat_all_types/',$val['img'],'">';
                echo '<p>',$val['brand'],'</p>';
                echo '</button></div>';
              }

            };
          ?>
        </div>
      </form>
    </div>
    <!-- フッター -->
    <div class="footer">
      <footer>
        <ul>
          <li>"Where&nbsp;there&nbsp;is&nbsp;a&nbsp;will,&nbsp;there&nbsp;is&nbsp;a&nbsp;way."</li>
          <li>2020&nbsp;|&nbsp;CatSearch</li>
        </ul>
      </footer>
    </div>
  </article>
</body>

</html>