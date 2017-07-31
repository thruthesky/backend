<?php
    $host = $_SERVER['HTTP_HOST'];
    if ( strpos( $host, 'katalkenglish.com' ) !== false ) $naver_login_client_id = "RQ5aql3x2TQKjwi2Pkh4";
    else if ( strpos( $host, 'iamtalkative.com' ) !== false ) $naver_login_client_id = "hLsDccGynf0Kci1AxLjL";
    else $naver_login_client_id = "RQ5aql3x2TQKjwi2Pkh4";
    $ip_address = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
?>
<script>
    var naver_login_client_id = "<?php echo $naver_login_client_id?>";
    var use_facebook = true;
    var client_ip_address = "<?php echo $ip_address?>";
</script>


  <script>

      var payment_customization = {
        customAmountInput: true,  /// To show a box that user can input amount.
        defaultMinutes: 'N',  /// If 0, then custom amout input will be selected by default. If 25, then, 25 will be selected by default.
        defaultDays: 5,       /// Default days to be selected.
        defaultMonths: 1,     /// Defalt months to be selected.
        text: {               /// Text(title) to display on the form.
          minutes: {
            25: '25분',
            // 50: '50분 (10% 할인)'
          },
          months: {
            1: '1개월',
            3: '3개월',
            6: '6개월 (5% DC)',
            12: '12개월 (10% DC)'
          },
          days: {
            5: '주 5 회',
            4: '주 4 회',
            3: '주 3 회'
          }
        },
        minutes_months_days : {
          25: { // minutes
            1: { // months
              5: 120000, // days
              4: 100000,
              3: 90000
            },
            3: {
              5: 360000,
              4: 300000,
              3: 270000
            },
            6: {
              5: 684000,
              4: 570000,
              3: 513000
            },
            12: {
              5: 1296000,
              4: 1080000,
              3: 972000
            }
          }
          // ,
          // 50: {
          //   1: {
          //     5: 216000,
          //     4: 194400,
          //     3: 172800
          //   },
          //   3: {
          //     5: 648000,
          //     4: 583200,
          //     3: 518400
          //   },
          //   6: {
          //     5: 1296000,
          //     4: 1166400,
          //     3: 1036800
          //   },
          //   12: {
          //     5: 2592000,
          //     4: 2332800,
          //     3: 2073600
          //   }
          // }
        }
      };
  </script>



