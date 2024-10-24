<?php
require __DIR__ . '/lib/autoload.php';
use YooKassa\Client;

add_action( 'wp_ajax_homework_payment', 'homework_payment' );
add_action( 'wp_ajax_nopriv_homework_payment', 'homework_payment' );

function homework_payment(){
  //получаем данные
  $u_id = $_POST['user_id'];
  $p_id = $_POST['post_id'];

  $this_practice = false;
  if(isset($_POST['is_practice'])){
    $this_practice = $_POST['is_practice'];
  }

  if(isset($_POST['is_crypt']) && $_POST['is_crypt']){
    $p_id = simple_decode($_POST['post_id']);
  }

  //если такого поста нет в массиве прочитанных - выходим
  $homework_arr = get_user_meta($u_id, 'homework_post_arr')[0];

  $price = get_post_meta($p_id, 'post_homework_price')[0];
  $client = new Client();
    $client->setAuth('909533', 'live_paHrzUHOTJm5HSR4NSzPIgv9VszM82P5k1VqFXydivI');
    $payment = $client->createPayment(
        array(
            'amount' => array(
                'value' => $price,
                'currency' => 'RUB',
            ),
            'confirmation' => array(
                'type' => 'redirect',
                'return_url' => 'https://itroscope.com/',
            ),
            'capture' => true,
            'description' => 'Заказ №1',
            'metadata' => array(
              'user_id' => $u_id,
              'post_id' => $p_id,
              'is_practice' => $this_practice,
            ),
        ),
        uniqid('', true)
    );
  echo $payment['confirmation']['confirmation_url'];
  die;
}

 ?>
