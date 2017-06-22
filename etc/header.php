<?php
    $host = $_SERVER['HTTP_HOST'];
    if ( strpos( $host, 'katalkenglish.com' ) !== false ) $naver_login_client_id = "RQ5aql3x2TQKjwi2Pkh4";
    else if ( strpos( $host, 'katalkenglish.com' ) !== false ) $naver_login_client_id = "hLsDccGynf0Kci1AxLjL";
    else $naver_login_client_id = "RQ5aql3x2TQKjwi2Pkh4";
?>
<script>
    var naver_login_client_id = "<?php echo $naver_login_client_id?>";
</script>
