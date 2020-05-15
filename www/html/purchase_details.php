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
// ログインチェックを行うため、セッションを開始する
session_start();

//悪意のあるユーザーかチェック
token_check();
//トークン変数を破棄
unset($_SESSION['csrf_token']);

if(is_logined() === false){
  // ログインしていない場合はログインページにリダイレクト
  redirect_to(LOGIN_URL);
}

// PDOを取得
$db = get_db_connect();
// PDOを利用してログインユーザーのデータを取得
$user = get_login_user($db);

$order_id = get_post('order_id');
$purchase_time = get_post('purchase_time');
$total = get_post('total');

// 購入明細を取得
if ($user['type'] === USER_TYPE_ADMIN){
  $details = get_open_details($db,$order_id);
} 
else {
  $details = get_open_detail($db,$user['user_id'],$order_id);
}

//$historys = get_session('history');

include_once VIEW_PATH . 'purchase_details_view.php';