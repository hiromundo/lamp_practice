<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'details.php';

session_start();

//悪意のあるユーザーかチェック
token_check();
//トークン変数を破棄
unset($_SESSION['csrf_token']);

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);

//ユーザーのカート一覧取得
$carts = get_user_carts($db, $user['user_id']);

if(insert_purchase_history($db,$user['user_id'],$carts) === false){
  set_error('データ取得に失敗しました。もう一度購入処理をお願いいたします');
  redirect_to(CART_URL);
};

if(purchase_carts($db, $carts) === false){
  set_error('商品が購入できませんでした。');
  redirect_to(CART_URL);
} 

$total_price = sum_carts($carts);

include_once '../view/finish_view.php';