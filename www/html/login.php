<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';

session_start();
//ワンタイムトークンの生成
create_csrf_token();
// dd($_SESSION);
// ログイン状態ならリダイレクト
if(is_logined() === true){
  //index.phpへリダイレクト
  redirect_to(HOME_URL);
}

include_once VIEW_PATH . 'login_view.php';