<?php
date_default_timezone_set("Asia/Seoul");
function echo_euckr($org_str) {
	// echo $org_str;
	$str = @iconv('utf8', 'euckr', $org_str);
	if ( $str ) echo $str;
	else echo $org_str;
}
function domain_name()
{
	if ( isset( $_SERVER['HTTP_HOST'] ) ) {
		$domain = $_SERVER['HTTP_HOST'];
		$domain = strtolower($domain);
		return $domain;
	}
	else return NULL;
}
function input_check( $name, $txt ) {
	if ( isset($_REQUEST[$name]) && $name ) {
  }
  else {
		echo <<<EOH
			<script>
				alert("$txt 정보가 올바르지 않습니다.");
				history.go(-1);
			</script>
EOH;
		exit;
  } 
}
header('Content-type: text/html; charset=euc-kr');

input_check('amount', '금액');
input_check('email', '이메일');
input_check('id', '아이디');
input_check('mobile', '전화번호');
input_check('name', '이름');



$StoreId 	= "thruthesky2";
$OrdNo 		= date("ymdhis");
$amt 		= $_GET['amount'];
$AGS_HASHDATA = md5($StoreId . $OrdNo . $amt); 


$MallUrl	= "https://" . domain_name();
$StoreNm	= "화상영어";
$ProdNm		= "화상영어수업료";
$UserEmail	= $_GET['email'];
$UserId		= $_GET['id'];
$OrdPhone	= $_GET['mobile'];


$date	= date("Y.m.d") . " ~ " . date("Y.m.d", time() + 60 * 60 * 24 * 180 );

$SubjectData = "$StoreNm;$ProdNm;$amt;$date";



$OrdNm		= $_GET['name'];
$OrdAddr	= "대한민국 $OrdNm $OrdPhone";

$Remark	= $OrdAddr;


?>
<html>
<head>
<meta charset="euc-kr">
<title>화상영어 수업료 결제</title>
<style type="text/css">

</style>
<script language=javascript src="https://www.allthegate.com/plugin/AGSWallet_ssl.js"></script>
<script language=javascript>
<!--
StartSmartUpdate();  

function Pay(form){
	
	if(form.Flag.value == "enable"){
		
			if(document.AGSPay == null || document.AGSPay.object == null){
				alert("플러그인 설치 후 다시 시도 하십시오.");
			}else{
				
				form.DeviId.value = "9000400001";
				
				if(parseInt(form.Amt.value) < 50000)
					form.QuotaInf.value = "0";
				else
					form.QuotaInf.value = "0:2:3:4:5:6:7:8:9:10:11:12";
				
				
				
				if(form.DeviId.value == "9000400002")
					form.NointInf.value = "ALL";
				   
				if(MakePayMessage(form) == true){										
					Disable_Flag(form);
					form.submit();
				}else{
					alert("지불에 실패하였습니다.");
				}
			}
		
	}
}

function Enable_Flag(form){
        form.Flag.value = "enable"
}

function Disable_Flag(form){
        form.Flag.value = "disable"
}


-->
</script>
</head>
<body topmargin=0 leftmargin=0 rightmargin=0 bottommargin=0 onload="javascript:Enable_Flag(frmAGS_pay);">


<!-- design -->
<style>
      body {
        margin: 0;
        padding: 0;
        font-family: "AppleGothic", "Malgun Gothic";
				font-size: 11pt;
      }
			a {
				text-decoration: none;
			}
      .top .menu {
        padding: 1em;
        height: 3em;
        overflow: hidden;
        background-color: rgba( 0, 0, 0, .8 );
      }
			.top .menu, .top td, .top a {
				color: white;
			}
      .top .menu table {
        margin: 0 auto;
        width: 100%;
        max-width: 912px;
      }

			h1.title {
				font-weight: 100;
				font-size: 2em;
			}
			.go-back {
				font-size: .9em;
				font-weight: 100;
				text-align: center;
				line-height: 140%;
			}
      .content {
        position: relative;
        margin: 3em auto 2em;
        padding: 1em;
        max-width: 912px;
        background-repeat: no-repeat;
        background-position: center 0;
        line-height: 180%;
        text-align: center;
      }
      .content h2 {
        margin-bottom: 20px;
				font-weight: 100;
				font-size: 1.4em;
      }
			.content .amount {
				font-weight: 100;
				font-size: 1.2em;
			}
      .logo img {
        width: 140px;
      }

.bottom-go-back {
	margin-top: 8em;
	padding: 1em;
	border: 0;
	background-color: #e8e8e8;
	text-align: center;
}
.bottom-go-back a {
	color: black;
}

.payment-button { text-align: center; }
.payment-button input {
	padding: 1em 2em;
	border: 0;
	background-color: #e3e3e3;
}

.click-again-text {
	margin-top: 2em;
	font-size: .85em;
	font-weight: normal;
	color: #ce3d06;
}

.phone-text {
	margin-top: 1em;
	font-size: .95em;
	font-weight: normal;
	color: #0b7ca9;
}
    </style>
		<div class="top">
      <div class="menu">
        <table cellpadding=0 cellspacing=0 border=0>
          <tr valign=top>
            <td><a href="https://iamtalkative.com"><span class="logo"><img src="https://iamtalkative.com/assets/images/logo/logo24.png"></span></a></td>
            <td>
								<h1 class="title">
									굿톡 화상영어 수업료 결제
								</h1>
            </td>
            <td class="go-back" valign="middle">
							<a href="https://iamtalkative.com">굿톡 화상영어<br>홈페이지로 돌아가기</a>
            </td>
          </tr>
        </table>
      </div><!--/menu-->
      <div class="content">
  			<h2><?php echo_euckr($_REQUEST['name'])?>님, 굿톡 화상영어 수업료를 결제합니다.</h2>
      	<div class="amount">결제 금액 : <?php echo number_format($_REQUEST['amount'])?>원</div>
      </div><!--/content-->
    </div>
<!-- eo design -->

<form name=frmAGS_pay method=post action=AGS_pay_ing.php>
<input type=hidden name=Job maxlength=20 value="onlycard">
<input type=hidden name=StoreId value="<?php echo $StoreId?>">
<input type=hidden name=StoreNm value="<?php echo $StoreNm?>">
<input type=hidden name=OrdNo value="<?php echo $OrdNo?>"> 
<input type=hidden name=Amt value="<?php echo $amt?>"> 
<input type=hidden name=UserId value="<?php echo $UserId?>">
<input type=hidden name=ProdNm  value="<?php echo $ProdNm?>">
<input type=hidden name=MallUrl value="<?php echo $MallUrl?>">
<input type=hidden name=UserEmail maxlength=50 value="<?php echo $UserEmail?>">
<input type=hidden name=SubjectData value="<?php echo $SubjectData?>">
<input type=hidden name=OrdNm maxlength=40 value="<?php echo $OrdNm?>">
<input type=hidden name=OrdPhone maxlength=21 value="<?php echo $OrdPhone?>">
<input type=hidden name=OrdAddr maxlength=100 value="<?php echo $OrdAddr?>">
<input type=hidden name=RcpNm maxlength=40 value="<?php echo $OrdNm?>">
<input type=hidden name=RcpPhone maxlength=21 value="<?php echo $OrdPhone?>">
<input type=hidden name=DlvAddr maxlength=100 value="<?php echo $OrdAddr?>">
<input type=hidden name=Remark maxlength=350 value="<?php echo $Remark?>">
<input type=hidden name=CardSelect value="">


				
<input type=hidden name=Flag value="">
<input type=hidden name=AuthTy value="">
<input type=hidden name=SubTy value="">
<input type=hidden name=AGS_HASHDATA value="<?=$AGS_HASHDATA?>">

<input type=hidden name=DeviId value="">			
<input type=hidden name=QuotaInf value="0">			
<input type=hidden name=NointInf value="NONE">		
<input type=hidden name=AuthYn value="">			
<input type=hidden name=Instmt value="">			
<input type=hidden name=partial_mm value="">		
<input type=hidden name=noIntMonth value="">		
<input type=hidden name=KVP_RESERVED1 value="">		
<input type=hidden name=KVP_RESERVED2 value="">		
<input type=hidden name=KVP_RESERVED3 value="">		
<input type=hidden name=KVP_CURRENCY value="">		
<input type=hidden name=KVP_CARDCODE value="">		
<input type=hidden name=KVP_SESSIONKEY value="">	
<input type=hidden name=KVP_ENCDATA value="">		
<input type=hidden name=KVP_CONAME value="">		
<input type=hidden name=KVP_NOINT value="">			
<input type=hidden name=KVP_QUOTA value="">			
<input type=hidden name=CardNo value="">			
<input type=hidden name=MPI_CAVV value="">			
<input type=hidden name=MPI_ECI value="">			
<input type=hidden name=MPI_MD64 value="">			
<input type=hidden name=ExpMon value="">			
<input type=hidden name=ExpYear value="">			
<input type=hidden name=Passwd value="">			
<input type=hidden name=SocId value="">


<input type=hidden name=ICHE_OUTBANKNAME value="">	
<input type=hidden name=ICHE_OUTACCTNO value="">
<input type=hidden name=ICHE_OUTBANKMASTER value="">
<input type=hidden name=ICHE_AMOUNT value="">		


<input type=hidden name=HP_SERVERINFO value="">		
<input type=hidden name=HP_HANDPHONE value="">		
<input type=hidden name=HP_COMPANY value="">		
<input type=hidden name=HP_IDEN value="">			
<input type=hidden name=HP_IPADDR value="">			


<input type=hidden name=ARS_PHONE value="">			
<input type=hidden name=ARS_NAME value="">			


<input type=hidden name=ZuminCode value="">
<input type=hidden name=VIRTUAL_CENTERCD value="">	
<input type=hidden name=VIRTUAL_NO value="">		

<input type=hidden name=mTId value="">	


<input type=hidden name=ES_SENDNO value="">			


<input type=hidden name=ICHE_SOCKETYN value="">		
<input type=hidden name=ICHE_POSMTID value="">
<input type=hidden name=ICHE_FNBCMTID value="">		
<input type=hidden name=ICHE_APTRTS value="">		
<input type=hidden name=ICHE_REMARK1 value="">		
<input type=hidden name=ICHE_REMARK2 value="">		
<input type=hidden name=ICHE_ECWYN value="">		
<input type=hidden name=ICHE_ECWID value="">		
<input type=hidden name=ICHE_ECWAMT1 value="">		
<input type=hidden name=ICHE_ECWAMT2 value="">		
<input type=hidden name=ICHE_CASHYN value="">		
<input type=hidden name=ICHE_CASHGUBUN_CD value="">	
<input type=hidden name=ICHE_CASHID_NO value="">	


<input type=hidden name=ICHEARS_SOCKETYN value="">	
<input type=hidden name=ICHEARS_ADMNO value="">		
<input type=hidden name=ICHEARS_POSMTID value="">
<input type=hidden name=ICHEARS_CENTERCD value="">	
<input type=hidden name=ICHEARS_HPNO value="">		


<div class="payment-button">
<input type="button" value="화상영어 수업료 결제하기" onclick="javascript:Pay(frmAGS_pay);">
<div class="click-again-text">수업료 결제창이 1분 이내에 뜨지 않으면 위의 버튼을 클릭 해 주세요.</div>
<div class="phone-text">결제가 어려우면 070-7893-1741 로 전화주세요.</div>
</div>


<div class="bottom-go-back">
	<a href="https://iamtalkative.com">굿톡 화상영어 홈페이지로 돌아가기</a>
</div>


</form>
<script>
	//parent.postMessage('payment-begin', '*');
	setTimeout( function() { Pay(frmAGS_pay); }, 100 );
</script>
</body>
</html>