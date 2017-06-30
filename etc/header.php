<?php
    $host = $_SERVER['HTTP_HOST'];
    if ( strpos( $host, 'katalkenglish.com' ) !== false ) $naver_login_client_id = "RQ5aql3x2TQKjwi2Pkh4";
    else if ( strpos( $host, 'iamtalkative.com' ) !== false ) $naver_login_client_id = "hLsDccGynf0Kci1AxLjL";
    else $naver_login_client_id = "RQ5aql3x2TQKjwi2Pkh4";
?>
<script>
    var payment_customization = {
        customInput: true,
        minutes: {
            25: {
                name: '25분',
                months: {
                    1: {
                        5: 120000,
                        4: 108000,
                        3: 96000
                    },
                    3: {
                        5: 360000,
                        4: 324000,
                        3: 288000
                    },
                    6: {
                        5: 720000,
                        4: 648000,
                        3: 576000
                    },
                    12: {
                        5: 1440000,
                        4: 1296000,
                        3: 1152000
                    }
                }
            },
            50: {
                name: '50분 (10% 할인)',
                months: {
                    1: {
                        5: 216000,
                        4: 194400,
                        3: 172800
                    },
                    3: {
                        5: 648000,
                        4: 583200,
                        3: 518400
                    },
                    6: {
                        5: 1296000,
                        4: 1166400,
                        3: 1036800
                    },
                    12: {
                        5: 2592000,
                        4: 2332800,
                        3: 2073600
                    }
                }
            }
        }
    };

    var naver_login_client_id = "<?php echo $naver_login_client_id?>";
</script>
