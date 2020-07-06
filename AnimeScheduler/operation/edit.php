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
  <title>編集・削除</title>
  <link href="" rel="stylesheet" type="text/css">
  <link href="" rel="stylesheet" type="text/css">
  <link href="" rel="stylesheet" type="text/css">
  <script type="text/javascript" src=""></script>
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
    
    <div class="conteiner">
      <?php
        echo "<p>",$_POST["edit"],"</p>";
        
      
      
      ?>
      
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