<?php
session_start();
require_once '../common/summary.php';

$pdo = new Dbget();
$pdo->connection();

$errorMessage = "";

if (isset($_POST['LoginButton'])){

  $userCount = mb_strlen($_POST['UserId']);
  $passCount = mb_strlen($_POST['PassWord']);

  if ($userCount === 0 OR $passCount === 0){

    $errorMessage = 'ユーザー名またはパスワードが入力されていません';

  } else {

    if ($userSearch = $pdo->loginJudg($_POST['UserId'])){

      if (password_verify($_POST['PassWord'], $userSearch['login_pass'])){

        session_regenerate_id(true);
        $_SESSION['name'] = $_POST['UserId'];

        date_default_timezone_set('Asia/Tokyo');
        $loginDay = date("Y-m-d H:i:s");
        $pdo->access($userSearch['id'], $loginDay);

        header('Location: ../operation/top.php');
        exit;

      } else {

        $errorMessage = 'ユーザーIDまたはパスワードが間違っています';

      }
    } else {

      $errorMessage = 'ユーザーIDまたはパスワードが間違っています';
      
    }
  }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>login</title>
  <link href="../common/reset.css" rel="stylesheet" type="text/css">
  <link href="../common/common.css" rel="stylesheet" type="text/css">
  <link href="../gate/guardian.css" rel="stylesheet" type="text/css">
  <link rel="icon" type="image/png" href="../other/img/chidori.png">
  <link rel="apple-touch-icon" type="image/png" href="../other/img/chidori.png">
</head>

<body>
  <div class="container">
    <article>
      <section>
        <form name="logincheck" action="" method="post">
          <div class="loginbox centered">
            <div class="loginbox__inputrelation">
              <div class="inputrelation--title centered">
                <h1>AnimeScheduler</h1>
              </div>
              <?php
                if(isset($errorMessage)){
              ?>
              <div class="inputrelation__error centered">
                <?php
                echo '<p>'.$errorMessage.'</p>';
              ?>
              </div>
              <?php
                }
              ?>
              <div class="inputrelation__userbox centered">
                <input class="inputrelation--input" type="text" name="UserId" placeholder="ユーザーID">
              </div>
              <div class="inputrelation__passbox centered">
                <input class="inputrelation--input" type="password" name="PassWord" placeholder="パスワード">
              </div>
              <div class="inputrelation__loginbuttonbox centered">
                <button class="inputrelation--button" type="submit" name="LoginButton">Login</butoon>
              </div>
            </div>
          </div>
        </form>
      </section>
    </article>
  </div>
</body>

</html>