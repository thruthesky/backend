<?php
function domain_name()
{
	if ( isset( $_SERVER['HTTP_HOST'] ) ) {
		$domain = $_SERVER['HTTP_HOST'];
		$domain = strtolower($domain);
		return $domain;
	}
	else return NULL;
}
header('Content-type: text/html; charset=euc-kr');
$StoreId 	= "thruthesky2";
$OrdNo 		= date("ymdhis");
$amt 		= $_GET['amount'];
$AGS_HASHDATA = md5($StoreId . $OrdNo . $amt); 


$MallUrl	= "https://" . domain_name();
$StoreNm	= "ȭ�󿵾�";
$ProdNm		= "ȭ�󿵾������";
$UserEmail	= $_GET['email'];
$UserId		= $_GET['id'];
$OrdPhone	= $_GET['mobile'];


$date	= date("Y.m.d") . " ~ " . date("Y.m.d", time() + 60 * 60 * 24 * 180 );

$SubjectData = "$StoreNm;$ProdNm;$amt;$date";



$OrdNm		= $_GET['name'];
$OrdAddr	= "���ѹα� $OrdNm $OrdPhone";

$Remark	= $OrdAddr;


?>
<html>
<head>
<meta charset="euc-kr">
<title>ȭ�󿵾� ������ ����</title>
<style type="text/css">

</style>
<script language=javascript src="https://www.allthegate.com/plugin/AGSWallet_ssl.js"></script>
<script language=javascript>
<!--
StartSmartUpdate();  

function Pay(form){
	
	if(form.Flag.value == "enable"){
		
			if(document.AGSPay == null || document.AGSPay.object == null){
				alert("�÷����� ��ġ �� �ٽ� �õ� �Ͻʽÿ�.");
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
					alert("���ҿ� �����Ͽ����ϴ�.");
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


<div style="margin-top: 100px; text-align: center;">
<input type="button" value="ȭ�󿵾� ������ �����ϱ�" onclick="javascript:Pay(frmAGS_pay);">
</div>

</form>
<script>
	parent.postMessage('payment-begin', '*');
	setTimeout( function() { Pay(frmAGS_pay); }, 100 );
</script>
</body>
</html> 