<?php

//*******************************************************************************
// MD5 ���� ������ ��ȣȭ ó��
// ���� : �������̵�(StoreId) + �ֹ���ȣ(OrdNo) + �����ݾ�(Amt)
//*******************************************************************************

$StoreId 	= "aegis";
$OrdNo 		= "1000000001";
$amt 		= "1000";

$AGS_HASHDATA = md5($StoreId . $OrdNo . $amt); 

?>

<html>
<head>
<title>�ô�����Ʈ</title>
<style type="text/css">
<!--
body { font-family:"����"; font-size:9pt; color:#333333; font-weight:normal; letter-spacing:0pt; line-height:180%; }
td { font-family:"����"; font-size:9pt; color:#333333; font-weight:normal; letter-spacing:0pt; line-height:180%; }
.clsright { padding-right:10px; text-align:right; }
.clsleft { padding-left:10px; text-align:left; }
-->
</style>
<script language=javascript src="http://www.allthegate.com/plugin/AGSWallet.js"></script>
<!-- �� UTF8 ��� �������� ������ ���۽� �Ʒ� ����� js ������ ����� ��!! -->
<!-- script language=javascript src="http://www.allthegate.com/plugin/AGSWallet_utf8.js"></script -->
<!-- Euc-kr �� �ƴ� �ٸ� charset �� �̿��� ��쿡�� AGS_pay_ing(����ó��������) ����� 
	[ AGS_pay.html �� ���� �Ѱܹ��� �������Ķ���� ] ����ο��� �Ķ���� ������ euc-kr��
	���ڵ� ��ȯ�� ���ֽñ� �ٶ��ϴ�.
<!-- �� SSL ������ �̿��� ��� �Ʒ� ����� js ������ ����� ��!! -->
<!-- script language=javascript src="https://www.allthegate.com/plugin/AGSWallet_ssl.js"></script -->
<script language=javascript>
<!--
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// �ô�����Ʈ �÷����� ��ġ�� Ȯ���մϴ�.
//////////////////////////////////////////////////////////////////////////////////////////////////////////////

StartSmartUpdate();  

function Pay(form){
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// MakePayMessage() �� ȣ��Ǹ� �ô�����Ʈ �÷������� ȭ�鿡 ��Ÿ���� Hidden �ʵ�
	// �� ���ϰ����� ä������ �˴ϴ�.
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	if(form.Flag.value == "enable"){
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// �Էµ� ����Ÿ�� ��ȿ���� �˻��մϴ�.
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		if(Check_Common(form) == true){
			//////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// �ô�����Ʈ �÷����� ��ġ�� �ùٸ��� �Ǿ����� Ȯ���մϴ�.
			//////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			if(document.AGSPay == null || document.AGSPay.object == null){
				alert("�÷����� ��ġ �� �ٽ� �õ� �Ͻʽÿ�.");
			}else{
				//////////////////////////////////////////////////////////////////////////////////////////////////////////////
				// �ô�����Ʈ �÷����� �������� �������� �����ϱ� JavaScript �ڵ带 ����ϰ� �ֽ��ϴ�.
				// ���������� �°� JavaScript �ڵ带 �����Ͽ� ����Ͻʽÿ�.
				//
				// [1] �Ϲ�/������ ��������
				// [2] �Ϲݰ����� �Һΰ�����
				// [3] �����ڰ����� �Һΰ����� ����
				// [4] ��������
				//////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
				//////////////////////////////////////////////////////////////////////////////////////////////////////////////
				// [1] �Ϲ�/������ �������θ� �����մϴ�.
				//
				// �Һ��Ǹ��� ��� �����ڰ� ���ڼ����Ḧ �δ��ϴ� ���� �⺻�Դϴ�. �׷���,
				// ������ �ô�����Ʈ���� ���� ����� ���ؼ� �Һ����ڸ� ���������� �δ��� �� �ֽ��ϴ�.
				// �̰�� �����ڴ� ������ �Һΰŷ��� �����մϴ�.
				//
				// ����)
				// 	(1) �Ϲݰ����� ����� ���
				// 	form.DeviId.value = "9000400001";
				//
				// 	(2) �����ڰ����� ����� ���
				// 	form.DeviId.value = "9000400002";
				//
				// 	(3) ���� ���� �ݾ��� 100,000�� �̸��� ��� �Ϲ��Һη� 100,000�� �̻��� ��� �������Һη� ����� ���
				// 	if(parseInt(form.Amt.value) < 100000)
				//		form.DeviId.value = "9000400001";
				// 	else
				//		form.DeviId.value = "9000400002";
				//////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
				form.DeviId.value = "9000400001";
				
				//////////////////////////////////////////////////////////////////////////////////////////////////////////////
				// [2] �Ϲ� �ҺαⰣ�� �����մϴ�.
				// 
				// �Ϲ� �ҺαⰣ�� 2 ~ 12�������� �����մϴ�.
				// 0:�Ͻú�, 2:2����, 3:3����, ... , 12:12����
				// 
				// ����)
				// 	(1) �ҺαⰣ�� �ϽúҸ� �����ϵ��� ����� ���
				// 	form.QuotaInf.value = "0";
				//
				// 	(2) �ҺαⰣ�� �Ͻú� ~ 12�������� ����� ���
				//		form.QuotaInf.value = "0:3:4:5:6:7:8:9:10:11:12";
				//
				// 	(3) �����ݾ��� ���������ȿ� ���� ��쿡�� �Һΰ� �����ϰ� �� ���
				// 	if((parseInt(form.Amt.value) >= 100000) || (parseInt(form.Amt.value) <= 200000))
				// 		form.QuotaInf.value = "0:2:3:4:5:6:7:8:9:10:11:12";
				// 	else
				// 		form.QuotaInf.value = "0";
				//////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
				//�����ݾ��� 5���� �̸����� �Һΰ����� ��û�Ұ�� ��������
				if(parseInt(form.Amt.value) < 50000)
					form.QuotaInf.value = "0";
				else
					form.QuotaInf.value = "0:2:3:4:5:6:7:8:9:10:11:12";
				
				////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				// [3] ������ �ҺαⰣ�� �����մϴ�.
				// (�Ϲݰ����� ��쿡�� �� ������ ������� �ʽ��ϴ�.)
				// 
				// ������ �ҺαⰣ�� 2 ~ 12�������� �����ϸ�, 
				// �ô�����Ʈ���� ������ �Һ� ������������ �����ؾ� �մϴ�.
				// 
				// 100:BC
				// 200:����
				// 201:NH 
				// 300:��ȯ
				// 310:�ϳ�SK
				// 400:�Ｚ
				// 500:����
				// 800:����
				// 900:�Ե�
				// 
				// ����)
				// 	(1) ��� �Һΰŷ��� �����ڷ� �ϰ� ���������� ALL�� ����
				// 	form.NointInf.value = "ALL";
				//
				// 	(2) ����ī�� Ư���������� �����ڸ� �ϰ� ������� ����(2:3:4:5:6����)
				// 	form.NointInf.value = "200-2:3:4:5:6";
				//
				// 	(3) ��ȯī�� Ư���������� �����ڸ� �ϰ� ������� ����(2:3:4:5:6����)
				// 	form.NointInf.value = "300-2:3:4:5:6";
				//
				// 	(4) ����,��ȯī�� Ư���������� �����ڸ� �ϰ� ������� ����(2:3:4:5:6����)
				// 	form.NointInf.value = "200-2:3:4:5:6,300-2:3:4:5:6";
				//	
				//	(5) ������ �ҺαⰣ ������ ���� ���� ��쿡�� NONE�� ����
				//	form.NointInf.value = "NONE";
				//
				//	(6) ��ī��� Ư���������� �����ڸ� �ϰ� �������(2:3:6����)
				//	form.NointInf.value = "100-2:3:6,200-2:3:6,201-2:3:6,300-2:3:6,310-2:3:6,400-2:3:6,500-2:3:6,800-2:3:6,900-2:3:6";
				//
				////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
				if(form.DeviId.value == "9000400002")
					form.NointInf.value = "ALL";
				   
				if(MakePayMessage(form) == true){										
					Disable_Flag(form);
					
					var openwin = window.open("AGS_progress.html","popup","width=300,height=160"); //"����ó����"�̶�� �˾�â���� �κ�
					
					form.submit();
				}else{
					alert("���ҿ� �����Ͽ����ϴ�.");// ��ҽ� �̵������� �����κ�
				}
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

function Check_Common(form){
	if(form.StoreId.value == ""){
		alert("�������̵� �Է��Ͻʽÿ�.");
		return false;
	}
	else if(form.StoreNm.value == ""){
		alert("�������� �Է��Ͻʽÿ�.");
		return false;
	}
	else if(form.OrdNo.value == ""){
		alert("�ֹ���ȣ�� �Է��Ͻʽÿ�.");
		return false;
	}
	else if(form.ProdNm.value == ""){
		alert("��ǰ���� �Է��Ͻʽÿ�.");
		return false;
	}
	else if(form.Amt.value == ""){
		alert("�ݾ��� �Է��Ͻʽÿ�.");
		return false;
	}
	else if(form.MallUrl.value == ""){
		alert("����URL�� �Է��Ͻʽÿ�.");
		return false;
	}
	return true;
}

function Display(form){
	if(form.Job.value == "onlycard" || form.TempJob.value == "onlycard"){
		document.all.card_hp.style.display= "";
		document.all.card.style.display= "";
		document.all.hp.style.display= "none";
		document.all.virtual.style.display= "none";
	}else if(form.Job.value == "onlyhp" || form.TempJob.value == "onlyhp"){
		document.all.card_hp.style.display= "";
		document.all.card.style.display= "none";
		document.all.hp.style.display= "";
		document.all.virtual.style.display= "none";
	}else if(form.Job.value == "onlyvirtual" || form.TempJob.value == "onlyvirtual" ){
		document.all.card_hp.style.display= "none";
		document.all.card.style.display= "";
		document.all.hp.style.display= "none";
		document.all.virtual.style.display= "";
	}else if(form.Job.value == "onlyiche" || form.TempJob.value == "onlyiche"  ){
		document.all.card_hp.style.display= "none";
		document.all.card.style.display= "none";
		document.all.hp.style.display= "none";
		document.all.virtual.style.display= "none";
	}else{
		document.all.card_hp.style.display= "";
		document.all.card.style.display= "";
		document.all.hp.style.display= "";
		document.all.virtual.style.display= "";
	}
}
-->
</script>
</head>
<!-- ����) onload �̺�Ʈ���� �Ʒ��� ���� javascript �Լ��� ȣ������ ���ʽÿ�. -->
<!-- onload="javascript:Enable_Flag(frmAGS_pay);Pay(frmAGS_pay);" -->
<body topmargin=0 leftmargin=0 rightmargin=0 bottommargin=0 onload="javascript:Enable_Flag(frmAGS_pay);">
<form name=frmAGS_pay method=post action=AGS_pay_ing.php>
<table border=0 width=100% height=100% cellpadding=0 cellspacing=0>
	<tr>
		<td align=center>
		<table width=650 border=0 cellpadding=0 cellspacing=0>
			<tr><td>&nbsp;</td></tr>
			<tr><td><hr></td></tr>
			<tr><td class=clsleft><b>���ҿ�û �׽�Ʈ������</b></td></tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td class=clsleft>
				1) �� ���ҿ�û �������� ������ �°� �����ϰ� �����Ͽ� ����Ͻʽÿ�.<br>
				2) �� ������������ �ô�����Ʈ �÷������� �ٿ�ε��Ͽ� ��ġ�ϵ��� �Ǿ� �ֽ��ϴ�. �ٿ�ε��Ŀ�  <font color=#006C6C>���Ȱ��â�� �߸� Ȯ�� ��ư("��")�� �����Ͽ�</font> �÷������� ��ġ�� �ֽʽÿ�. ���� ��ġ�� �����Ͽ��� ��� �������� <a href="http://www.allthegate.com/plugin/AGSPayPluginV10.exe"><font color=#006C6C>�ٿ�ε�</font></a>�Ͽ� ��ġ�� �ֽʽÿ�.<br>
				3) ���ҿ�û�� ���� �ʿ��� ������ ��� �Է��� '���ҿ�û'��ư�� Ŭ���Ͻø� �ô�����Ʈ �÷������� �����մϴ�.<br>
				4) �ſ�ī�常 ���� �� <font color=#006C6C>�������ҹ��</font>�� <font color=#006C6C><b>�ſ�ī��(����)</b></font>���� ������ �ֽʽÿ�.<br>
				5) DB �۾��� �Ͻ� ��� <font color=#006C6C>������������(rSuccYn)</font>�� Ȯ���Ŀ� �۾��Ͽ� �ֽʽÿ�.<br>
				6) �ڵ��� ���� ���� �ô�����Ʈ���� �߱޹���[�ڵ����������̵�,��й�ȣ,��ǰ�ڵ�,��ǰŸ��]�� �Է��Ͽ� �ֽʽÿ�.<br>
				7) ������ �Է½� <font color=#006C6C>"|"</font>�� �ô�����Ʈ���� �����ڷ� ����ϴ� �����̹Ƿ� �Է����� ���� �ֽʽÿ�.
				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td class=clsleft>�� ǥ�ô� �ʼ� �Է»����Դϴ�. </td></tr>
			<tr><td><hr></td></tr>
			<tr>
				<td>
				<table width=650 border=0 cellpadding=0 cellspacing=0>
					<tr>
						<td class=clsleft colspan=3><font color=#006C6C>+ ���� ��� ����</font></td>
					</tr>
					<tr>
						<td width=170 class=clsleft>�� ���ҹ��</td>
						<td width=300>
						<!-- ������ü,�ڵ��������� ������� �ʴ� ������ ���ҹ���� �� �ſ�ī��(����)���� �����Ͻñ� �ٶ��ϴ�. -->
						<!-- �ſ�ī�常 ����ϵ��� ���� <input type=hidden name=Job value="onlycard"> -->
						<!-- ������ü�� ����ϵ��� ���� <input type=hidden name=Job value="onlyiche"> -->
						<!-- �ڵ��������� ����ϵ��� ���� <input type=hidden name=Job value="onlyhp"> -->
						<select name=Job style=width:150px onchange="javascript:Display(frmAGS_pay);">
							<option value="" selected>�����Ͻʽÿ�.
							<option value="card">�ſ�ī��
							<option value="iche">������ü
							<option value="virtual">�������
							<option value="hp">�ڵ�������
							<option value="ars">ARS
							<option value="onlycard">�ſ�ī�� (����)
							<option value="onlyiche">������ü (����)
							<option value="onlyvirtual">������� (����)
							<option value="onlyhp">�ڵ������� (����)
							<option value="onlyars">ARS (����)
							<option value="onlycardselfnormal">�ſ�ī�� (�Ϲ�����)
							<option value="onlycardselfescrow">�ſ�ī�� (����ũ������)
							<option value="onlyicheselfnormal">������ü (�Ϲ�����)
							<option value="onlyicheselfescrow">������ü (����ũ������)
							<option value="onlyvirtualselfnormal">������� (�Ϲ�����)
							<option value="onlyvirtualselfescrow">������� (����ũ������)
							<option value="onlyichebanknormal">������ü/���ͳݹ�ŷ (�Ϲ�����)
							<option value="onlyichebankescrow">������ü/���ͳݹ�ŷ (����ũ������)
							<option value="onlyichetelnormal">������ü/�ڷ���ŷ (�Ϲ�����)
							<option value="onlyichetelescrow">������ü/�ڷ���ŷ (����ũ������)
						</select>
						</td>
						<td width=180></td>
					</tr>
					<tr>
						<td class=clsleft>���ҹ�� �����Է� </td>
						<td><input type=text style=width:100px name=TempJob maxlength=20 value=""></td>
						<td class=clsleft width=180>��) card:iche</td>
					</tr>
					<tr>
						<td class=clsleft >�� �������̵� (20)</td>
						<!--�������̵� �ǰŷ� ��ȯ�Ŀ��� �߱޹��� ���̵�� �ٲٽñ� �ٶ��ϴ�.-->
						<td colspan=2><input type=text style=width:100px name=StoreId maxlength=20 value="aegis"></td>
					</tr>
					<tr>
						<td class=clsleft>�� �ֹ���ȣ (40)</td>
						<td colspan=2><input type=text style=width:100px name=OrdNo maxlength=40 value="1000000001"></td>
					</tr>
					<tr>
						<td class=clsleft>�� �ݾ� (12)</td>
						<td><input type=text style=width:100px name=Amt maxlength=12 value="1000">��</td>
						<td class=clsleft>��) �ݾ� �޸�(,)�ԷºҰ�</td>
					</tr>
					<tr>
						<td class=clsleft>�� ������ (50)</td>
						<td colspan=2><input type=text style=width:300px name=StoreNm value="��ǻ�Ͱ���"></td>
					</tr>
					<tr>
						<td class=clsleft>�� ��ǰ�� (300)</td>
						<td colspan=2><input type=text style=width:300px name=ProdNm maxlength=300 value="���콺"></td>
					</tr>
					<tr>
						<td class=clsleft>�� ����URL (50)</td>
						<!-- ����) ����Ȩ�������ּҸ� �ݵ�� �Է��� �ֽʽÿ�. -->
						<!-- (���Է½� Ư�� ī��� �ſ�ī�� ���� �� ������� ������ �̷����� ���� �� �ֽ��ϴ�.) -->
						<td><input type=text style=width:300px name=MallUrl value="http://www.allthegate.com"></td>
						<td class=clsleft>��) http://www.abc.com</td>
					</tr>
					<tr>
						<td class=clsleft>�ֹ����̸��� (50)</td>
						<td colspan=2><input type=text style=width:300px name=UserEmail maxlength=50 value="test@test.com"></td>
					</tr>
					<tr>
						<!-- ����â ������ܿ� ������ �ΰ��̹���(85 * 38)�� ǥ���� �� �ֽ��ϴ�. -->
						<!-- �߸��� ���� �Է��ϰų� ���Է½� �ô�����Ʈ�� �ΰ� ǥ�õ˴ϴ�. -->
						<td class=clsleft>�����ΰ��̹��� URL</td>
						<td colspan=2><input type=text style=width:400px name=ags_logoimg_url maxlength=200 value="http://www.allthegate.com/hyosung/images/aegis_logo.gif"></td>
					</tr>
					<tr>
						<td class=clsleft>����â�����Է�</td>
						<!-- ������ 1�������� 5�� �̳��̸�, ������;��ǰ��;�����ݾ�;�����Ⱓ; ������ �Է��� �ּž� �մϴ�. -->
						<!-- �Է� ��)��ü��;�ǸŻ�ǰ;���ݾ�;�����Ⱓ; -->
						<td><input type=text style=width:300px name=SubjectData value="��ü��;�ǸŻ�ǰ;���ݾ�;2012.09.01 ~ 2012.09.30;"></td>
						<td width=170 class=clsleft>��)��ü��;�ǸŻ�ǰ;���ݾ�;�����Ⱓ;</td>
					</tr>
				</table>
				<div id="card_hp" style="display:'';"> 
				<table width=650 border=0 cellpadding=0 cellspacing=0>
					<tr>
						<td width=156 class=clsleft>ȸ�����̵� (20)</td>
						<!-- [�ſ�ī��, �ڵ���] ������ [���ݿ������ڵ�����]�� ����Ͻô� ��쿡 �ݵ�� �Է��� �ֽñ� �ٶ��ϴ�. -->
						<td colspan=2><input type=text style=width:100px name=UserId maxlength=20 value="test"></td>
					</tr>
				</table>
				</div>
				<div id="card" style="display:'';"> 
				<table width=650 border=0 cellpadding=0 cellspacing=0>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td class=clsleft colspan=3><font color=#006C6C>+ ī�� & ������� ���� ��� ����</font></td>
					</tr>
					<tr>
						<td width=160 class=clsleft>�ֹ��ڸ� (40)</td>
						<td width=300><input type=text style=width:100px name=OrdNm maxlength=40 value="ȫ�浿"></td>
						<td width=190></td>
					</tr>
					<tr>
						<td class=clsleft>�ֹ��ڿ���ó (21)</td>
						<td colspan=2><input type=text style=width:100px name=OrdPhone maxlength=21 value="02-111-1111"></td>
					</tr>
               		<tr>
						<td class=clsleft>�ֹ����ּ� (100)</td><!-- ��������߰� -->
						<td colspan=2><input type=text style=width:300px name=OrdAddr maxlength=100 value="����� ������ û�㵿"></td>
					</tr>
					<tr>
						<td class=clsleft>�����ڸ� (40)</td>
						<td colspan=2><input type=text style=width:100px name=RcpNm maxlength=40 value="��浿"></td>
					</tr>
					<tr>
						<td class=clsleft>�����ڿ���ó (21)</td>
						<td colspan=2><input type=text style=width:100px name=RcpPhone maxlength=21 value="02-111-1111"></td>
					</tr>
					<tr>
						<td class=clsleft>������ּ� (100)</td>
						<td colspan=2><input type=text style=width:300px name=DlvAddr maxlength=100 value="����� ������ û�㵿"></td>
					</tr>
					<tr>
						<td class=clsleft>��Ÿ�䱸���� (350)</td>
						<td colspan=2><input type=text style=width:300px name=Remark maxlength=350 value="���Ŀ� ��ۿ��"></td>
					</tr>
					 <tr>
						<td class=clsleft>ī��缱��</td>
						<td colspan=2><input type=text style=width:300px name=CardSelect value=""></td>
						<!-- ����â�� Ư��ī�常 ǥ�����Դϴ�. 
						          ����� ��)  BC, ������ ����ϰ��� �ϴ� ��� �� 100:200
											    ���� �� ����ϰ��� �ϴ� ��� �� 200
							 ��� ����ϰ��� �� ������ �ƹ� ���� �Է����� �ʽ��ϴ�.
							 ī��纰 �ڵ�� �Ŵ��󿡼� Ȯ���� �ֽñ� �ٶ��ϴ�. -->
			  </tr>
				</table>
				</div>
				<div id="hp" style="display:'';"> 
				<table width=650 border=0 cellpadding=0 cellspacing=0>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td class=clsleft colspan=3><font color=#006C6C>+ �ڵ��� ���� ��� ����</font></td>
					</tr>
					<tr>
						<td width=158 class=clsleft>CP���̵� (10)</td>
						<!-- CP���̵� �ڵ��� ���� �ǰŷ� ��ȯ�Ŀ��� �߱޹����� CPID�� �����Ͽ� �ֽñ� �ٶ��ϴ�. -->
						<td width=300><input type=text style=width:100px name=HP_ID maxlength=10 value=""></td>
						<td width=192></td>
					</tr>
					<tr>
						<td class=clsleft>CP��й�ȣ (10)</td>
						<!-- CP��й�ȣ�� �ڵ��� ���� �ǰŷ� ��ȯ�Ŀ��� �߱޹����� ��й�ȣ�� �����Ͽ� �ֽñ� �ٶ��ϴ�. -->
						<td colspan=2><input type=text style=width:100px name=HP_PWD maxlength=10 value=""></td>
					</tr>
					<tr>
						<td class=clsleft>SUB-CP���̵� (10)</td>
						<!-- SUB-CPID�� �ڵ��� ���� �ǰŷ� ��ȯ�Ŀ� �߱޹����� ������ �Է��Ͽ� �ֽñ� �ٶ��ϴ�. -->
						<td colspan=2><input type=text style=width:100px name=HP_SUBID maxlength=10 value=""></td>
					</tr>
					<tr>
						<td class=clsleft>��ǰ�ڵ� (10)</td>
						<!-- ��ǰ�ڵ带 �ڵ��� ���� �ǰŷ� ��ȯ�Ŀ��� �߱޹����� ��ǰ�ڵ�� �����Ͽ� �ֽñ� �ٶ��ϴ�. -->
						<td colspan=2><input type=text style=width:100px name=ProdCode maxlength=10 value=""></td>
					</tr>
					<tr>
						<td class=clsleft>��ǰ����</td>
						<td colspan=2>
						<!-- ��ǰ������ �ڵ��� ���� �ǰŷ� ��ȯ�Ŀ��� �߱޹����� ��ǰ������ �����Ͽ� �ֽñ� �ٶ��ϴ�. -->
						<!-- �Ǹ��ϴ� ��ǰ�� ������(������)�� ��� = 1, �ǹ�(��ǰ)�� ��� = 2 -->
						<select name=HP_UNITType style=width:100px>
							<option value="1">������:1
							<option value="2">�ǹ�:2
						</select>
						</td>
					</tr>
				</table>
				</div>
				<div id="virtual" style="display:'';"> 
				<table width=650 border=0 cellpadding=0 cellspacing=0>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td class=clsleft colspan=3><font color=#006C6C>+ ������� ���� ��� ����</font></td>
					</tr>
               		<tr>
						<td width=180 class=clsleft>�뺸������ (100)</td>
						<!-- ������� �������� ��/��� �뺸�� ���� �ʼ� �Է� ���� �Դϴ�. -->
						<!-- �������ּҴ� �������ּҸ� ������ '/'���� �ּҸ� �����ֽø� �˴ϴ�. -->
						<td width=300><input type=text style=width:300px name=MallPage value="/mall/AGS_VirAcctResult.php"></td>
						<td width=170 class=clsleft>��) /ab/AGS_VirAcctResult.jsp</td>
					</tr>
					<tr>
						<td width=180 class=clsleft>������� �Աݿ����� (8)</td>
						<!-- ������� �������� �Աݰ����� ������ �����ϴ� ����Դϴ�. -->
						<!-- �߱����ڷκ��� �ִ� 15�� �̳��θ� �����ϼž� �մϴ�. -->
						<!-- ���� �Է����� ���� ���, �ڵ����� �߱����ڷκ��� 5�� ���ķ� �����˴ϴ�. -->
						<td width=300><input type=text style=width:300px name=VIRTUAL_DEPODT value=""></td>
						<td width=170 class=clsleft>��) 20100120</td>
					</tr>
				</table>
				</div>
				</td>
			</tr>
			<tr><td><hr></td></tr>
			<tr>
				<td align=center>
				<input type="button" value="���ҿ�û" onclick="javascript:Pay(frmAGS_pay);">
				<!--
				<a href="javascript:Pay(frmAGS_pay);"><img src="button.gif" border="0"></a>
				-->
				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
		</table>
		</td>
	</tr>
</table>




<!-- ��ũ��Ʈ �� �÷����ο��� ���� �����ϴ� Hidden �ʵ�  !!������ �Ͻðų� �������� ���ʽÿ�-->

<!-- �� ���� ���� ��� ���� -->
<input type=hidden name=Flag value="">				<!-- ��ũ��Ʈ������뱸���÷��� -->
<input type=hidden name=AuthTy value="">			<!-- �������� -->
<input type=hidden name=SubTy value="">				<!-- ����������� -->
<input type=hidden name=AGS_HASHDATA value="<?=$AGS_HASHDATA?>">	<!-- ��ȣȭ HASHDATA -->

<!-- �ſ�ī�� ���� ��� ���� -->
<input type=hidden name=DeviId value="">			<!-- (�ſ�ī�����)		�ܸ�����̵� -->
<input type=hidden name=QuotaInf value="0">			<!-- (�ſ�ī�����)		�Ϲ��Һΰ����������� -->
<input type=hidden name=NointInf value="NONE">		<!-- (�ſ�ī�����)		�������Һΰ����������� -->
<input type=hidden name=AuthYn value="">			<!-- (�ſ�ī�����)		�������� -->
<input type=hidden name=Instmt value="">			<!-- (�ſ�ī�����)		�Һΰ����� -->
<input type=hidden name=partial_mm value="">		<!-- (ISP���)			�Ϲ��ҺαⰣ -->
<input type=hidden name=noIntMonth value="">		<!-- (ISP���)			�������ҺαⰣ -->
<input type=hidden name=KVP_RESERVED1 value="">		<!-- (ISP���)			RESERVED1 -->
<input type=hidden name=KVP_RESERVED2 value="">		<!-- (ISP���)			RESERVED2 -->
<input type=hidden name=KVP_RESERVED3 value="">		<!-- (ISP���)			RESERVED3 -->
<input type=hidden name=KVP_CURRENCY value="">		<!-- (ISP���)			��ȭ�ڵ� -->
<input type=hidden name=KVP_CARDCODE value="">		<!-- (ISP���)			ī����ڵ� -->
<input type=hidden name=KVP_SESSIONKEY value="">	<!-- (ISP���)			��ȣȭ�ڵ� -->
<input type=hidden name=KVP_ENCDATA value="">		<!-- (ISP���)			��ȣȭ�ڵ� -->
<input type=hidden name=KVP_CONAME value="">		<!-- (ISP���)			ī��� -->
<input type=hidden name=KVP_NOINT value="">			<!-- (ISP���)			������/�Ϲݿ���(������=1, �Ϲ�=0) -->
<input type=hidden name=KVP_QUOTA value="">			<!-- (ISP���)			�Һΰ��� -->
<input type=hidden name=CardNo value="">			<!-- (�Ƚ�Ŭ��,�Ϲݻ��)	ī���ȣ -->
<input type=hidden name=MPI_CAVV value="">			<!-- (�Ƚ�Ŭ��,�Ϲݻ��)	��ȣȭ�ڵ� -->
<input type=hidden name=MPI_ECI value="">			<!-- (�Ƚ�Ŭ��,�Ϲݻ��)	��ȣȭ�ڵ� -->
<input type=hidden name=MPI_MD64 value="">			<!-- (�Ƚ�Ŭ��,�Ϲݻ��)	��ȣȭ�ڵ� -->
<input type=hidden name=ExpMon value="">			<!-- (�Ϲݻ��)			��ȿ�Ⱓ(��) -->
<input type=hidden name=ExpYear value="">			<!-- (�Ϲݻ��)			��ȿ�Ⱓ(��) -->
<input type=hidden name=Passwd value="">			<!-- (�Ϲݻ��)			��й�ȣ -->
<input type=hidden name=SocId value="">				<!-- (�Ϲݻ��)			�ֹε�Ϲ�ȣ/����ڵ�Ϲ�ȣ -->

<!-- ������ü ���� ��� ���� -->
<input type=hidden name=ICHE_OUTBANKNAME value="">	<!-- ��ü��������� -->
<input type=hidden name=ICHE_OUTACCTNO value="">	<!-- ��ü���¿������ֹι�ȣ -->
<input type=hidden name=ICHE_OUTBANKMASTER value=""><!-- ��ü���¿����� -->
<input type=hidden name=ICHE_AMOUNT value="">		<!-- ��ü�ݾ� -->

<!-- �ڵ��� ���� ��� ���� -->
<input type=hidden name=HP_SERVERINFO value="">		<!-- �������� -->
<input type=hidden name=HP_HANDPHONE value="">		<!-- �ڵ�����ȣ -->
<input type=hidden name=HP_COMPANY value="">		<!-- ��Ż��(SKT,KTF,LGT) -->
<input type=hidden name=HP_IDEN value="">			<!-- �����û�� -->
<input type=hidden name=HP_IPADDR value="">			<!-- ���������� -->

<!-- ARS ���� ��� ���� -->
<input type=hidden name=ARS_PHONE value="">			<!-- ARS��ȣ -->
<input type=hidden name=ARS_NAME value="">			<!-- ��ȭ�����ڸ� -->

<!-- ������� ���� ��� ���� -->
<input type=hidden name=ZuminCode value="">			<!-- ��������Ա����ֹι�ȣ -->
<input type=hidden name=VIRTUAL_CENTERCD value="">	<!-- ������������ڵ� -->
<input type=hidden name=VIRTUAL_NO value="">		<!-- ������¹�ȣ -->

<input type=hidden name=mTId value="">	

<!-- ����ũ�� ���� ��� ���� -->
<input type=hidden name=ES_SENDNO value="">			<!-- ����ũ��������ȣ -->

<!-- ������ü(����) ���� ��� ���� -->
<input type=hidden name=ICHE_SOCKETYN value="">		<!-- ������ü(����) ��� ���� -->
<input type=hidden name=ICHE_POSMTID value="">		<!-- ������ü(����) �̿����ֹ���ȣ -->
<input type=hidden name=ICHE_FNBCMTID value="">		<!-- ������ü(����) FNBC�ŷ���ȣ -->
<input type=hidden name=ICHE_APTRTS value="">		<!-- ������ü(����) ��ü �ð� -->
<input type=hidden name=ICHE_REMARK1 value="">		<!-- ������ü(����) ��Ÿ����1 -->
<input type=hidden name=ICHE_REMARK2 value="">		<!-- ������ü(����) ��Ÿ����2 -->
<input type=hidden name=ICHE_ECWYN value="">		<!-- ������ü(����) ����ũ�ο��� -->
<input type=hidden name=ICHE_ECWID value="">		<!-- ������ü(����) ����ũ��ID -->
<input type=hidden name=ICHE_ECWAMT1 value="">		<!-- ������ü(����) ����ũ�ΰ����ݾ�1 -->
<input type=hidden name=ICHE_ECWAMT2 value="">		<!-- ������ü(����) ����ũ�ΰ����ݾ�2 -->
<input type=hidden name=ICHE_CASHYN value="">		<!-- ������ü(����) ���ݿ��������࿩�� -->
<input type=hidden name=ICHE_CASHGUBUN_CD value="">	<!-- ������ü(����) ���ݿ��������� -->
<input type=hidden name=ICHE_CASHID_NO value="">	<!-- ������ü(����) ���ݿ������ź�Ȯ�ι�ȣ -->

<!-- �ڷ���ŷ-������ü(����) ���� ��� ���� -->
<input type=hidden name=ICHEARS_SOCKETYN value="">	<!-- �ڷ���ŷ������ü(����) ��� ���� -->
<input type=hidden name=ICHEARS_ADMNO value="">		<!-- �ڷ���ŷ������ü ���ι�ȣ -->
<input type=hidden name=ICHEARS_POSMTID value="">	<!-- �ڷ���ŷ������ü �̿����ֹ���ȣ -->
<input type=hidden name=ICHEARS_CENTERCD value="">	<!-- �ڷ���ŷ������ü �����ڵ� -->
<input type=hidden name=ICHEARS_HPNO value="">		<!-- �ڷ���ŷ������ü �޴�����ȣ -->

<!-- ��ũ��Ʈ �� �÷����ο��� ���� �����ϴ� Hidden �ʵ�  !!������ �Ͻðų� �������� ���ʽÿ�-->

</form>
</body>
</html> 