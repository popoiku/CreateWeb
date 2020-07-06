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
  <link href="../02_css/details.css" rel="stylesheet" type="text/css">
  <link href="../00_img/nikukyuu_kai.png" rel="icon" type="image/png">
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
            <li><a href="../01_html/top.php">back&nbsp;to&nbsp;top</a></li>
          </ul>
        </nav>
      </header>
    </div>
    <!-- コンテナ― -->
    <div class="container">
      <?php
        if (isset($_REQUEST['catinfo'])){
          $id_result = $pdo->id_search($_REQUEST['catinfo']);
          // echo $id_result[0]['id']; 単体で取得
      ?>
      <div class="result">
        <div class="result__img">
          <div class="imgblock">
            <img src="../00_img/cat_all_types/<?php echo $id_result[0]['img']; ?>" class="imgblock--img">
            <p>
              引用元：<a href="<?php echo $id_result[0]['source']; ?>"><?php echo $id_result[0]['source']; ?></a>
            </p>
          </div>
        </div>
        <div class="result__list">
          <div class="listblock">
            <table class="listblock__item">
              <tr>
                <th>品種名</th>
                <td><?php echo $id_result[0]['brand']; ?></td>
              </tr>
              <tr>
                <th>原産</th>
                <td><?php echo $id_result[0]['origin']; ?></td>
              </tr>
              <tr>
                <th>毛</th>
                <td><?php echo $id_result[0]['hair']; ?></td>
              </tr>
              <tr>
                <th>タイプ</th>
                <td><?php echo $id_result[0]['type']; ?></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      <?php
        } else {
          echo '<div class="error">';
          echo '<p>Topページからやりなおしてください。</p>';
          echo '</div>';
        }
      ?>
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