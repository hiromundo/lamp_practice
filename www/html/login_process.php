<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

session_start();

//悪意のあるユーザーかチェック
token_check();
//トークン変数を破棄
unset($_SESSION['csrf_token']);
//dd($_SESSION);


if(is_logined() === true){
  // セッションがあれば、index.phpにリダレクト
  redirect_to(HOME_URL);
}
// ユーザ名、パスワードをPOSTで取得
$name = get_post('name');
$password = get_post('password');
// PDOを取得
$db = get_db_connect();

//ログイン情報を照合
$user = login_as($db, $name, $password);
//dd($_SESSION);
if( $user === false){
  set_error('ログインに失敗しました。');
  redirect_to(LOGIN_URL);
}

set_message('ログインしました。');

//ユーザーtypeの照合
if ($user['type'] === USER_TYPE_ADMIN){
  // admin.phpへリダイレクト
  redirect_to(ADMIN_URL);
}
redirect_to(HOME_URL);