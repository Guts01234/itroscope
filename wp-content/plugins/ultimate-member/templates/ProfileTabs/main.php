<?php 
 if ( ! defined( 'ABSPATH' ) ) exit;
//функция для отображения статистики на профиле;

add_action('um_profile_content_main', 'add_stats_to_profile');


function add_stats_to_profile(){

  $user_id = um_profile_id();
  

  if(is_company($user_id)){
    do_action('profile_main_company', $user_id);
  }else{



  $now_post_arr = get_user_meta($user_id, 'post_read_arr')[0];
  $now_sfer_arr = get_user_meta($user_id, 'sfeta_read_arr', true);
  $now_problem_arr = get_user_meta($user_id, 'problem_read_arr')[0];
  $now_navik_arr = get_user_meta($user_id, 'navik_read_arr')[0];

  $hwp = count(get_post_homework_arr($user_id));
  $rp = count(get_readed_arr($user_id));

  if(!$rp){
    $rp = 1;
  }
  if(!$hwp){
    $hwp = 1;
  }

  ?>
  <div class="bar_progress" data-hw='<?php echo $hwp; ?>' data-rp='<?php echo $rp ?>'>
    <div class='left' style="width:<?php echo (100*($rp / ($rp+$hwp))); ?>%">
      <p>Теоретик</p>
    </div>
    <div class='right' style="width:<?php echo 100*($hwp / ($rp+$hwp)); ?>%">
      <p>Практик</p>
    </div>
  </div>
  <div class="statistic_profile">
  <div class="teoriya">
  <div class='sfer_stats'>
    <h2>Теория:</h2>
    <?php
      if(0){
        ?><p>Нет данных о статистике</p><?php
      }
      else{
        ?>
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <div id="container"></div>
        <script type="text/javascript">
        Highcharts.chart('container', {
      chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
      },
      legend: {
            itemStyle: {
                fontSize: '14px'
            }
        },
      title: {
        text: ''
      },
      tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
      },
      accessibility: {
        point: {
          valueSuffix: '%'
        }
      },
      plotOptions: {
        pie: {
          showInLegend: true,
          allowPointSelect: true,
          cursor: 'pointer',
          dataLabels: {
            enabled: false,
          }
        }
      },
      series: [{
        name: 'Сферы',
        colorByPoint: true,
        data: [
          <?php
          $terms = get_terms([
            'taxonomy'  => 'sfera',
          ]);
          if(is_array($now_sfer_arr)){
            $c = count($now_sfer_arr);
          }
          else{
            $c = 0;
          }
          if(!is_array($now_sfer_arr)){
            $now_sfer_arr = array();
          }


          foreach ($terms as $term) {
            $e = 0;
            if(array_key_exists($term->name, $now_sfer_arr)){
              $e += $now_sfer_arr[$term->name];
            }

            $resp = ($e + 1) / ($c+4);

            echo '{name: "'. $term->name . '", y: ' . $resp  . '},' . PHP_EOL;
          }
           ?>
        ]
      }]
      });
        </script>
        <?php

      }

     ?>
  </div>
  <div class='sfer_stats'>
    <p class='stat_name' >Знаю о навыках:</p>
    <?php
      if(!is_array($now_navik_arr)){
        ?><p>Нет данных о статистике</p><?php
      }
      else{
        arsort($now_navik_arr);
        foreach ($now_navik_arr as $key => $value) {
          echo '<p>'. $key . ': ' . $value;
        }
      }

     ?>
  </div>
  <div class='sfer_stats'>
    <p class='stat_name'>Знаю о проблемах:</p>
    <?php
      if(!is_array($now_problem_arr)){
        ?><p>Нет данных о статистике</p><?php
      }
      else{
        arsort($now_problem_arr);
        foreach ($now_problem_arr as $key => $value) {
          echo '<p>'. $key . ': ' . $value;
        }
      }

     ?>
  </div>
</div>
  <?php
  //Скопировал блок для практики
  $now_post_arr = get_user_meta($user_id, 'homework_post_arr')[0];
  $now_sfer_arr = get_user_meta($user_id, 'practice_sfera')[0];
  $now_problem_arr = get_user_meta($user_id, 'practice_problem')[0];
  $now_navik_arr = get_user_meta($user_id, 'practice_navik')[0];
  ?>


  <div class="practic">
  <div class='sfer_stats'>
    <h2>Практика:</h2>
    <?php
      if(0){
        ?><p>Нет данных о статистике</p><?php
      }
      else{
        ?>
        <div id="container2"></div>
        <script type="text/javascript">
        Highcharts.chart('container2', {
      chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
      },
      legend: {
            itemStyle: {
                fontSize: '14px'
            }
        },
      title: {
        text: ''
      },
      tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
      },
      accessibility: {
        point: {
          valueSuffix: '%'
        }
      },
      plotOptions: {
        pie: {
          showInLegend: true,
          allowPointSelect: true,
          cursor: 'pointer',
          dataLabels: {
            enabled: false,
          }
        }
      },
      series: [{
        name: 'Сферы',
        colorByPoint: true,
        data: [
          <?php
          $terms = get_terms([
            'taxonomy'  => 'sfera',
          ]);
          if(is_array($now_sfer_arr)){
            $c = count($now_sfer_arr);
          }
          else{
            $c = 0;
          }
          if(!is_array($now_sfer_arr)){
            $now_sfer_arr = array();
          }


          foreach ($terms as $term) {
            $e = 0;
            if(array_key_exists($term->name, $now_sfer_arr)){
              $e += $now_sfer_arr[$term->name];
            }elseif (array_key_exists(mb_strtolower($term->name), $now_sfer_arr)) {
              $e += $now_sfer_arr[mb_strtolower($term->name)];
            }

            $resp = ($e + 1) / ($c+4);

            echo '{name: "'. $term->name . '", y: ' . $resp  . '},' . PHP_EOL;
          }
           ?>
        ]
      }]
      });
        </script>
        <?php

      }

     ?>
  </div>
  <div class='sfer_stats'>
    <p class='stat_name' >Применяю навыки:</p>
    <?php
      if(!is_array($now_navik_arr)){
        ?><p>Нет данных о статистике</p><?php
      }
      else{
        arsort($now_navik_arr);
        foreach ($now_navik_arr as $key => $value) {
          echo '<p>'. $key . ': ' . $value;
        }
      }

     ?>
  </div>
  <div class='sfer_stats'>
    <p class='stat_name'>Решаю проблемы:</p>
    <?php
      if(!is_array($now_problem_arr)){
        ?><p>Нет данных о статистике</p><?php
      }
      else{
        arsort($now_problem_arr);
        foreach ($now_problem_arr as $key => $value) {
          echo '<p>'. $key . ': ' . $value;
        }
      }

     ?>
  </div>
  </div>
  </div>
  <?php

}
}

?>