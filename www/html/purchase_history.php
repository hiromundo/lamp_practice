<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';
// 汎用関数ファイルを読み込み
require_once MODEL_PATH  .'functions.php';
// userデータに関するファイルを読み込み
require_once MODEL_PATH .'user.php';
// itemデータに関するファイルを読み込み
require_once MODEL_PATH .'item.php';
// detailデータに関するファイルを読み込み
require_once MODEL_PATH .'details.php';

session_start();

// tokenの生成
create_csrf_token();

if(is_logined() === false){
  
  redirect_to(LOGIN_URL);
}

// PDOを取得
$db = get_db_connect();
// PDOを利用してログインユーザーのデータを取得
$user = get_login_user($db);

if ($user['type'] === USER_TYPE_ADMIN){
  $historys = get_purchase_historys($db);
} else {
  // 購入履歴を取得
  $historys = get_purchase_history($db,$user['user_id']);
}
//set_session('history',$historys);



include_once VIEW_PATH . 'purchase_history_view.php';