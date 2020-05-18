<?php
require_once MODEL_PATH .'functions.php';
require_once MODEL_PATH . 'db.php';



// ログインユーザーの購入明細データを取得
function get_open_detail($db,$user_id,$order_id){
  $sql = '
    SELECT 
      items.name
      , d.price
      , d.amount
      , (d.amount * d.price) AS subtotal
    FROM 
      purchase_details AS d
    INNER JOIN 
      items
    ON 
      d.item_id = items.item_id
    INNER JOIN 
      purchase_history AS h
    ON 
      d.order_id = h.order_id
    INNER JOIN 
      users
    ON 
      h.user_id = users.user_id
    WHERE 
      users.user_id = ?
    AND 
      h.order_id = ?;
  ';
  return fetch_all_query($db,$sql,array($user_id,$order_id));
}
// 管理者用購入明細を取得
function get_open_details($db,$order_id){
  $sql = '
    SELECT 
      items.name
      , d.price
      , d.amount
      , (d.amount * d.price) AS subtotal
    FROM 
      purchase_details AS d
    INNER JOIN 
      items
    ON 
      d.item_id = items.item_id
    INNER JOIN 
      purchase_history AS h
    ON 
      d.order_id = h.order_id
    INNER JOIN 
      users
    ON 
      h.user_id = users.user_id
    WHERE
      h.order_id = ?;
  ';
  return fetch_all_query($db,$sql,array($order_id));
}
// ログインユーザーの購入履歴を取得
function get_purchase_history($db,$user_id = NULL){
  $sql = '
    SELECT 
      d.order_id, purchase_time, SUM(d.amount * d.price) AS total
    FROM
      purchase_details AS d
    INNER JOIN 
      purchase_history AS h
    ON 
      d.order_id = h.order_id 
    ';
    if ($user_id !== NULL){
    $sql .= 'WHERE
              h.user_id = ? 
    ';
    }
    $sql .= 'GROUP BY 
              d.order_id
             ORDER BY
              h.purchase_time ASC 
    ';
    if($user_id === NULL) {
      $bind = [];
    }else {
      $bind = array($user_id);
    }
    
  return fetch_all_query($db,$sql,$bind);
}
// // 管理者用購入履歴を取得
// function get_purchase_historys($db){
//   $sql = '
//   SELECT 
//     d.order_id, purchase_time, SUM(d.amount * d.price) AS total
//   FROM
//     purchase_details AS d
//   INNER JOIN 
//     purchase_history AS h
//   ON 
//     d.order_id = h.order_id
//   GROUP BY
//     d.order_id
//   ORDER BY
//     h.purchase_time ASC
//   ';
//   return fetch_all_query($db,$sql);
// }

function insert_purchase_history($db,$user_id,$carts){
  $db->beginTransaction();
  try{
      $time = date('Y-m-d H:i:s');
      insert_history($db,$user_id,$time);
      //最後の行のorder_idを取得
      $order_id = $db->lastInsertID('order_id');
      insert_details($db,$order_id,$carts);

      $db->commit();
      return true;
      
    } catch(PDOException $e){
      $db->rollback();
      return false;
    }

}
function insert_history($db,$user_id,$time){
  $sql = "
    INSERT INTO
      purchase_history(
          user_id,
          purchase_time
          )
    VALUES(?,?)
  ";
  return execute_query($db,$sql,array($user_id,$time));

}

function insert_details($db,$order_id,$carts){
  foreach($carts as $cart){
    insert_detail($db,$order_id,$cart['item_id'],$cart['amount'],$cart['price']);
  }
  
}

function insert_detail($db,$order_id,$item_id,$amount,$price){
  $sql = "
    INSERT INTO 
      purchase_details(
        order_id,
        item_id,
        amount,
        price
        )
    VALUES(?,?,?,?)
  ";
  return execute_query($db,$sql,array($order_id,$item_id,$amount,$price));
}