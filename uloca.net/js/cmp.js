
// 기업정보 공통코드 , 표 만들기---------------------------------------------------------------


// 기업공개코드
var code_enp_fcd = ["01","02","03","04","05","06","07","08","09","10","11","12","13","14","16","17","18","19"]
var name_enp_fcd = ["주식회사","합명회사","합자회사","유한회사","비영리재단법인","비영리사단법인","조합","외국법인","의료법인","학교법인","종교법인","학술장학법인","사회복지법인","기타공익법인","개인기업","개인동업기업","법인격없는 단체","유한책임회사"]
function CC_enp_fcd(cc) {
	for (i=0;i<code_enp_fcd.length ; i++)
	{
		if (code_enp_fcd[i] == cc) return " ("+name_enp_fcd[i]+")"; 
	}
	return "";
}

// 기업형태코드 --------------
var code_ipo_cd = ["0","1","2","3","4","5","6","7"]
var name_ipo_cd = ["전체","유가증권시장","코스닥시장","금감위등록","외감","개인사업자","코넥스시장"]
function CC_ipo_cd(cc) {
	for (i=0;i<code_ipo_cd.length ; i++)
	{
		if (code_ipo_cd[i] == cc) return " ("+name_ipo_cd[i]+")";
	}
	return "";
}

// 기업상태 --------------
var code_enp_scd = ["01","02","03","04","05","06"]
var name_enp_scd = ["정상","법인전환","휴업","폐업","청산/해산","피흡수합병"]
function CC_enp_scd(cc) {
	for (i=0;i<code_enp_scd.length ; i++)
	{
		if (code_enp_scd[i] == cc) return " ("+name_enp_scd[i]+")";
	}
	return "";
}

// 기업규모 --------------
var code_enp_sze = ["01","02","03","04","05","06"]
var name_enp_sze = ["대기업","중기업","소기업","한시성중소기업","중견기업","소상공인"]
function CC_enp_sze(cc) {
	for (i=0;i<code_enp_sze.length ; i++)
	{
		if (code_enp_sze[i] == cc) return " ("+name_enp_sze[i]+")";
	}
	return "";
}


// KED신용등급코드 --------------
var code_cr_grd = ["01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","91","92","93","94","95","99"]
var name_cr_grd = ["AAA","AA+","AA","AA-","A+","A","A-","BBB+","BBB","BBB-","BB+","BB","BB-","B+","B","B-","CCC+","CCC","CCC-","CC","C","D","R1","R2","R3","F1","F2","NR"]
function CC_cr_grd(cc) {
	for (i=0;i<code_cr_grd.length ; i++)
	{
		if (code_cr_grd[i] == cc) return " ("+name_cr_grd[i]+")";
	}
	return "";
}

// KED등급구분코드 --------------
var code_grd_cls = ["01","02","03","04","05","10","91","92","93","94"]
var name_grd_cls = ["본평가","정기평가","수시평가","예비등급","조사등급","모형등급","CCRS1","CCRS2","CCRS3","CCRS9"]
function CC_grd_cls(cc) {
	for (i=0;i<code_grd_cls.length ; i++)
	{
		if (code_grd_cls[i] == cc) return " ("+name_grd_cls[i]+")";
	}
	return "";
}

// 현금흐름등급 cf_anal_summ->cf_anal4 --------------
var code_cf_anal4 = ["CR1","CR2","CR3","CR4","CR5","CR6","NF","NR"]
var name_cf_anal4 = ["매우양호","양호","보통이상","보통이상","불량","매우불량","판정제외","판정보류"]
function CC_cf_anal4(cc) {
	for (i=0;i<code_cf_anal4.length ; i++)
	{
		if (code_cf_anal4[i] == cc) return " ("+name_cf_anal4[i]+")";
	}
	return "";
}

function setDate(sDate,sep) {
	if (sDate.length<5) return sDate;

	if (sDate.length == 6)
	{
		return sDate.substr(0,2)+sep+sDate.substr(2,2)+sep+sDate.substr(4,2);
	} else return sDate.substr(0,4)+sep+sDate.substr(4,2)+sep+sDate.substr(6,2);
}

	function xmlText(E017,field,type) {
		// type = 1 textContent
		// type = 2 innerHTML
		// type = 3 date
		// type = 4 number
		// type = 5 100만원 단위 반올림
		// type = 6 년도
		// type = func
		// clog("ln100::type="+type + ", field[0]=" + field[0]);
		var val = "";
		try
		{
			if (E017.getElementsByTagName(field)[0] == "undefined") return "";
			if (type == 1) val = E017.getElementsByTagName(field)[0].textContent;
			else if (type == 2) val = E017.getElementsByTagName(field)[0].textContent;
			else if (type == 3) val = setDate(E017.getElementsByTagName(field)[0].textContent,"-");
			else if (type == 4) val = number_format(E017.getElementsByTagName(field)[0].textContent);
			else if (type == 5) val = Math.round(eval(E017.getElementsByTagName(field)[0].textContent)/1000000); // 100만원
			else if (type == 6) val = E017.getElementsByTagName(field)[0].textContent.substr(0,4); // year
			else val = E017.getElementsByTagName(field)[0].textContent + " " + type(E017.getElementsByTagName(field)[0].textContent);
			return val;
		}
		catch (e)
		{
			// clog(field+" "+type+" e="+e.message);
			return "";
		}
		

	}

	function make_basic(E017) {
			document.getElementById("bzno").innerHTML = '&nbsp'+xmlText(E017,'bzno',1);	// bzno;
			document.getElementById("enp_nm").innerHTML = '&nbsp'+xmlText(E017,'enp_nm',1);	//enp_nm; //decode_utf8(enp_nm);
			document.getElementById("cono_pid").innerHTML = '&nbsp'+ E017.getElementsByTagName('cono_pid')[0].textContent;
			document.getElementById("eng_enp_nm").innerHTML = '&nbsp'+E017.getElementsByTagName('eng_enp_nm')[0].textContent;	// 영문명
			document.getElementById("reper_nm").innerHTML = '&nbsp'+E017.getElementsByTagName('reper_nm')[0].textContent;
			document.getElementById("enp_fcd").innerHTML = '&nbsp'+E017.getElementsByTagName('enp_fcd')[0].textContent + CC_enp_fcd(E017.getElementsByTagName('enp_fcd')[0].textContent);
			document.getElementById("ipo_cd").innerHTML = '&nbsp'+E017.getElementsByTagName('ipo_cd')[0].textContent + CC_ipo_cd(E017.getElementsByTagName('ipo_cd')[0].textContent);
			document.getElementById("hpage_url").innerHTML = '&nbsp'+E017.getElementsByTagName('hpage_url')[0].textContent;
			document.getElementById("em_cnt").innerHTML = '&nbsp'+E017.getElementsByTagName('em_cnt')[0].textContent; 
			document.getElementById("acct_mm").innerHTML = '&nbsp'+E017.getElementsByTagName('acct_mm')[0].textContent;
			document.getElementById("group_nm").innerHTML = '&nbsp'+E017.getElementsByTagName('group_nm')[0].textContent;
			document.getElementById("bzc_cd").innerHTML = '&nbsp'+E017.getElementsByTagName('bzc_cd')[0].textContent;
			document.getElementById("bzc_nm").innerHTML = '&nbsp'+E017.getElementsByTagName('bzc_nm')[0].textContent;
			document.getElementById("zip").innerHTML = '&nbsp'+E017.getElementsByTagName('zip')[0].textContent;
			document.getElementById("addr1").innerHTML = '&nbsp'+E017.getElementsByTagName('addr1')[0].textContent;
			document.getElementById("addr2").innerHTML = '&nbsp'+E017.getElementsByTagName('addr2')[0].textContent;
			document.getElementById("tel_no").innerHTML = '&nbsp'+E017.getElementsByTagName('tel_no')[0].textContent;
			document.getElementById("fax_no").innerHTML = '&nbsp'+E017.getElementsByTagName('fax_no')[0].textContent;
			document.getElementById("hpage_url").innerHTML = '&nbsp'+E017.getElementsByTagName('hpage_url')[0].textContent;
			document.getElementById("email").innerHTML = '&nbsp'+E017.getElementsByTagName('email')[0].textContent;
			document.getElementById("major_pd").innerHTML = '&nbsp'+E017.getElementsByTagName('major_pd')[0].textContent;
			document.getElementById("mtx_bnk_nm").innerHTML = '&nbsp'+E017.getElementsByTagName('mtx_bnk_nm')[0].textContent;	// 주거래은행
			// document.getElementById("enp_scd").innerHTML = '&nbsp'+xmlText(E017,'enp_scd',CC_enp_scd); //E017.getElementsByTagName('enp_scd')[0].textContent + CC_enp_scd(E017.getElementsByTagName('enp_scd')[0].textContent);
			document.getElementById("enp_scd").innerHTML = '&nbsp'+E017.getElementsByTagName('enp_scd')[0].textContent + CC_enp_scd(E017.getElementsByTagName('enp_scd')[0].textContent); // 기업상태
			document.getElementById("enp_scd_chg_dt").innerHTML = '&nbsp'+setDate(E017.getElementsByTagName('enp_scd_chg_dt')[0].textContent,'-'); // 기업상태 변경일
			// document.getElementById("enp_sze").innerHTML = '&nbsp'+xmlText(E017,'enp_sze',CC_enp_sze); //E017.getElementsByTagName('enp_sze')[0].textContent +  CC_enp_sze(E017.getElementsByTagName('enp_sze')[0].textContent);
			document.getElementById("enp_sze").innerHTML = '&nbsp'+E017.getElementsByTagName('enp_sze')[0].textContent +  CC_enp_sze(E017.getElementsByTagName('enp_sze')[0].textContent); // 기업규모
			document.getElementById("std_dt").innerHTML = '&nbsp'+setDate(E017.getElementsByTagName('std_dt')[0].textContent,'-');
			
			// 기업신용등급은 (white)표시하지 않음 - 기업데이터와 협의 함 -by jsj 20191119
			document.getElementById("cr_grd").innerHTML = '&nbsp'+E017.getElementsByTagName('cr_grd')[0].textContent + CC_cr_grd(E017.getElementsByTagName('cr_grd')[0].textContent);
			document.getElementById("cr_grd_dtl").innerHTML = '&nbsp'+E017.getElementsByTagName('cr_grd_dtl')[0].textContent;
			document.getElementById("grd_cls").innerHTML = '&nbsp'+E017.getElementsByTagName('grd_cls')[0].textContent+ CC_grd_cls(E017.getElementsByTagName('grd_cls')[0].textContent);
			
			// document.getElementById("evl_dt").innerHTML = '&nbsp'+xmlText(E017,'evl_dt',3); //setDate(E017.getElementsByTagName('evl_dt')[0].textContent,'-');
			// document.getElementById("sttl_base_dt").innerHTML = '&nbsp'+xmlText(E017,'sttl_base_dt',3); //setDate(E017.getElementsByTagName('sttl_base_dt')[0].innerHTML,'-');
			document.getElementById("evl_dt").innerHTML = '&nbsp'+ setDate(E017.getElementsByTagName('evl_dt')[0].textContent,'-');
			document.getElementById("sttl_base_dt").innerHTML = '&nbsp'+ setDate(E017.getElementsByTagName('sttl_base_dt')[0].textContent,'-');

		}
		function make_etc(E017) { // 기타
			// clog("기타");
			E017_sth = E017.getElementsByTagName('sth')[0];
			document.getElementById("sth_nm1").innerHTML = '&nbsp'+xmlText(E017_sth,'sth_nm1',1)+'&nbsp('+xmlText(E017_sth,'sth_eqrt1',1)+")"; 
			document.getElementById("sth_nm2").innerHTML = '&nbsp'+xmlText(E017_sth,'sth_nm2',1)+'&nbsp('+xmlText(E017_sth,'sth_eqrt2',1)+")";
			document.getElementById("sth_nm3").innerHTML = '&nbsp'+xmlText(E017_sth,'sth_nm3',1)+'&nbsp('+xmlText(E017_sth,'sth_eqrt3',1)+")";

			E017_renp = E017.getElementsByTagName('renp')[0];
			document.getElementById("renp_nm1").innerHTML = '&nbsp'+xmlText(E017_renp,'renp_nm1',1)+'&nbsp('+xmlText(E017_renp,'renp_eqrt1',1)+")"; 
			document.getElementById("renp_nm2").innerHTML = '&nbsp'+xmlText(E017_renp,'renp_nm2',1)+'&nbsp('+xmlText(E017_renp,'renp_eqrt2',1)+")";
			document.getElementById("renp_nm3").innerHTML = '&nbsp'+xmlText(E017_renp,'renp_nm3',1)+'&nbsp('+xmlText(E017_renp,'renp_eqrt3',1)+")";

			E017_customer = E017.getElementsByTagName('customer')[0];
			document.getElementById("customer_nm1").innerHTML = '&nbsp'+xmlText(E017_customer,'customer_nm1',1)+'&nbsp('+xmlText(E017_customer,'customer_rt1',1)+")"; 
			document.getElementById("customer_nm2").innerHTML = '&nbsp'+xmlText(E017_customer,'customer_nm2',1)+'&nbsp('+xmlText(E017_customer,'customer_rt2',1)+")";
			document.getElementById("customer_nm3").innerHTML = '&nbsp'+xmlText(E017_customer,'customer_nm3',1)+'&nbsp('+xmlText(E017_customer,'customer_rt3',1)+")";
			
			E017_supplier = E017.getElementsByTagName('supplier')[0];
			document.getElementById("supplier_nm1").innerHTML = '&nbsp'+xmlText(E017_supplier,'supplier_nm1',1)+'&nbsp('+xmlText(E017_supplier,'supplier_rt1',1)+")"; 
			document.getElementById("supplier_nm2").innerHTML = '&nbsp'+xmlText(E017_supplier,'supplier_nm2',1)+'&nbsp('+xmlText(E017_supplier,'supplier_rt2',1)+")";
			document.getElementById("supplier_nm3").innerHTML = '&nbsp'+xmlText(E017_supplier,'supplier_nm3',1)+'&nbsp('+xmlText(E017_supplier,'supplier_rt3',1)+")";

			E017_opnn = E017.getElementsByTagName('opnn')[0];
			document.getElementById("opnn_enp").innerHTML = '&nbsp'+xmlText(E017_opnn,'opnn_enp',1);
			document.getElementById("opnn_reper").innerHTML = '&nbsp'+xmlText(E017_opnn,'opnn_reper',1);
			document.getElementById("opnn_sales").innerHTML = '&nbsp'+xmlText(E017_opnn,'opnn_sales',1);
		}
		function make_fs_summ(E017) { // 요약재무제표
			// clog("요약재무제표");
			E017_fs_summ = E017.getElementsByTagName('fs_summ')[0];
			document.getElementById("fs_acct_dt").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs_acct_dt',3);
			document.getElementById("fs1_acct_dt").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs1_acct_dt',3);
			document.getElementById("fs2_acct_dt").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs2_acct_dt',3);

			document.getElementById("fs_val1").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs_val1',4);
			document.getElementById("fs1_val1").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs1_val1',4);
			document.getElementById("fs2_val1").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs2_val1',4);

			document.getElementById("fs_val2").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs_val2',4);
			document.getElementById("fs1_val2").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs1_val2',4);
			document.getElementById("fs2_val2").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs2_val2',4);
			document.getElementById("fs_val3").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs_val3',4);
			document.getElementById("fs1_val3").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs1_val3',4);
			document.getElementById("fs2_val3").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs2_val3',4);
			document.getElementById("fs_val4").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs_val4',4);
			document.getElementById("fs1_val4").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs1_val4',4);
			document.getElementById("fs2_val4").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs2_val4',4);
			document.getElementById("fs_val5").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs_val5',4);
			document.getElementById("fs1_val5").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs1_val5',4);
			document.getElementById("fs2_val5").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs2_val5',4);
			document.getElementById("fs_val6").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs_val6',4);
			document.getElementById("fs1_val6").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs1_val6',4);
			document.getElementById("fs2_val6").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs2_val6',4);
			document.getElementById("fs_val7").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs_val7',4);
			document.getElementById("fs1_val7").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs1_val7',4);
			document.getElementById("fs2_val7").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs2_val7',4);
			document.getElementById("fs_val8").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs_val8',4);
			document.getElementById("fs1_val8").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs1_val8',4);
			document.getElementById("fs2_val8").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs2_val8',4);
			document.getElementById("fs_val9").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs_val9',4);
			document.getElementById("fs1_val9").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs1_val9',4);
			document.getElementById("fs2_val9").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs2_val9',4);
			document.getElementById("fs_val10").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs_val10',4);
			document.getElementById("fs1_val10").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs1_val10',4);
			document.getElementById("fs2_val10").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs2_val10',4);
			document.getElementById("fs_val11").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs_val11',4);
			document.getElementById("fs1_val11").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs1_val11',4);
			document.getElementById("fs2_val11").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs2_val11',4);
			document.getElementById("fs_val12").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs_val12',4);
			document.getElementById("fs1_val12").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs1_val12',4);
			document.getElementById("fs2_val12").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs2_val12',4);
			document.getElementById("fs_val13").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs_val13',4);
			document.getElementById("fs1_val13").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs1_val13',4);
			document.getElementById("fs2_val13").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs2_val13',4);
			document.getElementById("fs_val14").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs_val14',4);
			document.getElementById("fs1_val14").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs1_val14',4);
			document.getElementById("fs2_val14").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs2_val14',4);
			document.getElementById("fs_val15").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs_val15',4);
			document.getElementById("fs1_val15").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs1_val15',4);
			document.getElementById("fs2_val15").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs2_val15',4);
			document.getElementById("fs_val16").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs_val16',4);
			document.getElementById("fs1_val16").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs1_val16',4);
			document.getElementById("fs2_val16").innerHTML = '&nbsp'+xmlText(E017_fs_summ,'fs2_val16',4);
		}

		

		function make_fr_summ(E017) { // 요약재무비율
			// clog("요약재무비율");
			E017_fr_summ = E017.getElementsByTagName('fr_summ')[0];
			document.getElementById("fr_acct_dt").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr_acct_dt',3)+'&nbsp(%)';
			document.getElementById("fr1_acct_dt").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr1_acct_dt',3)+'&nbsp(%)';
			document.getElementById("fr2_acct_dt").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr2_acct_dt',3)+'&nbsp(%)';

			document.getElementById("fr_val1").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr_val1',1);
			document.getElementById("fr1_val1").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr1_val1',1);
			document.getElementById("fr2_val1").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr2_val1',1);

			document.getElementById("fr_val2").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr_val2',2);
			document.getElementById("fr1_val2").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr1_val2',2);
			document.getElementById("fr2_val2").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr2_val2',2);
			document.getElementById("fr_val3").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr_val3',2);
			document.getElementById("fr1_val3").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr1_val3',2);
			document.getElementById("fr2_val3").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr2_val3',2);
			document.getElementById("fr_val4").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr_val4',2);
			document.getElementById("fr1_val4").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr1_val4',2);
			document.getElementById("fr2_val4").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr2_val4',2);
			document.getElementById("fr_val5").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr_val5',2);
			document.getElementById("fr1_val5").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr1_val5',2);
			document.getElementById("fr2_val5").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr2_val5',2);
			document.getElementById("fr_val6").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr_val6',2);
			document.getElementById("fr1_val6").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr1_val6',2);
			document.getElementById("fr2_val6").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr2_val6',2);
			document.getElementById("fr_val7").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr_val7',2);
			document.getElementById("fr1_val7").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr1_val7',2);
			document.getElementById("fr2_val7").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr2_val7',2);
			document.getElementById("fr_val8").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr_val8',2);
			document.getElementById("fr1_val8").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr1_val8',2);
			document.getElementById("fr2_val8").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr2_val8',2);
			document.getElementById("fr_val9").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr_val9',2);
			document.getElementById("fr1_val9").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr1_val9',2);
			document.getElementById("fr2_val9").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr2_val9',2);
			document.getElementById("fr_val10").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr_val10',2);
			document.getElementById("fr1_val10").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr1_val10',2);
			document.getElementById("fr2_val10").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr2_val10',2);
			document.getElementById("fr_val11").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr_val11',2);
			document.getElementById("fr1_val11").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr1_val11',2);
			document.getElementById("fr2_val11").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr2_val11',2);
			document.getElementById("fr_val12").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr_val12',2);
			document.getElementById("fr1_val12").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr1_val12',2);
			document.getElementById("fr2_val12").innerHTML = '&nbsp'+xmlText(E017_fr_summ,'fr2_val12',2);
		}
		function make_cf_anal_summ(E017) { // 요약현금흐름분석
			// clog("요약현금흐름분석");
			E017_anal_summ = E017.getElementsByTagName('cf_anal_summ')[0];
			document.getElementById("cf_acct_dt").innerHTML = '&nbsp'+xmlText(E017_anal_summ,'cf_acct_dt',3);
			document.getElementById("cf1_acct_dt").innerHTML = '&nbsp'+xmlText(E017_anal_summ,'cf1_acct_dt',3);
			document.getElementById("cf2_acct_dt").innerHTML = '&nbsp'+xmlText(E017_anal_summ,'cf2_acct_dt',3);

			document.getElementById("cf_anal1").innerHTML = '&nbsp'+xmlText(E017_anal_summ,'cf_anal1',4);
			document.getElementById("cf1_anal1").innerHTML = '&nbsp'+xmlText(E017_anal_summ,'cf1_anal1',4);
			document.getElementById("cf2_anal1").innerHTML = '&nbsp'+xmlText(E017_anal_summ,'cf2_anal1',4);
			document.getElementById("cf_anal2").innerHTML = '&nbsp'+xmlText(E017_anal_summ,'cf_anal2',4);
			document.getElementById("cf1_anal2").innerHTML = '&nbsp'+xmlText(E017_anal_summ,'cf1_anal2',4);
			document.getElementById("cf2_anal2").innerHTML = '&nbsp'+xmlText(E017_anal_summ,'cf2_anal2',4);
			document.getElementById("cf_anal3").innerHTML = '&nbsp'+xmlText(E017_anal_summ,'cf_anal3',4);
			document.getElementById("cf1_anal3").innerHTML = '&nbsp'+xmlText(E017_anal_summ,'cf1_anal3',4);
			document.getElementById("cf2_anal3").innerHTML = '&nbsp'+xmlText(E017_anal_summ,'cf2_anal3',4);
			document.getElementById("cf_anal4").innerHTML = '&nbsp'+xmlText(E017_anal_summ,'cf_anal4',CC_cf_anal4);
			document.getElementById("cf1_anal4").innerHTML = '&nbsp'+xmlText(E017_anal_summ,'cf1_anal4',CC_cf_anal4);
			document.getElementById("cf2_anal4").innerHTML = '&nbsp'+xmlText(E017_anal_summ,'cf2_anal4',CC_cf_anal4);
		}
// ------------------- cmp Chart

		var fs_summ_json = []
		function make_fs_summ_json(E017) { // 요약재무제표 json data
			E017_fs = E017.getElementsByTagName('fs_summ')[0];
			//fs_summ_json = []
			// 단위 100만원
			var text = '';
			if (eval(E017_fs.getElementsByTagName('fs_val3')[0].textContent) > 10000000)
			{	// 1000만원 이상
				text = ' [' +
				'{ "fs_val3":"'+xmlText(E017_fs,'fs_val3',5)+'", "fs_val6":"'+xmlText(E017_fs,'fs_val6',5)+'", "fs_val8":"'+xmlText(E017_fs,'fs_val8',5)+'", "fs_val9":"'+xmlText(E017_fs,'fs_val9',5)+'", "fs_val10":"'+xmlText(E017_fs,'fs_val10',5)+'", "fs_val16":"'+xmlText(E017_fs,'fs_val16',5)+'", "fs_acct_dt":"'+xmlText(E017_fs,'fs_acct_dt',6)+'" },' +
				'{ "fs_val3":"'+xmlText(E017_fs,'fs1_val3',5)+'", "fs_val6":"'+xmlText(E017_fs,'fs1_val6',5)+'", "fs_val8":"'+xmlText(E017_fs,'fs1_val8',5)+'", "fs_val9":"'+xmlText(E017_fs,'fs1_val9',5)+'", "fs_val10":"'+xmlText(E017_fs,'fs1_val10',5)+'", "fs_val16":"'+xmlText(E017_fs,'fs1_val16',5)+'", "fs_acct_dt":"'+xmlText(E017_fs,'fs1_acct_dt',6)+'" },' +
				'{ "fs_val3":"'+xmlText(E017_fs,'fs2_val3',5)+'", "fs_val6":"'+xmlText(E017_fs,'fs2_val6',5)+'", "fs_val8":"'+xmlText(E017_fs,'fs2_val8',5)+'", "fs_val9":"'+xmlText(E017_fs,'fs2_val9',5)+'", "fs_val10":"'+xmlText(E017_fs,'fs2_val10',5)+'", "fs_val16":"'+xmlText(E017_fs,'fs2_val16',5)+'", "fs_acct_dt":"'+xmlText(E017_fs,'fs2_acct_dt',6)+'" }' +
				
			']';
			} else {	// 1000만원 이하
				text = ' [' +
				'{ "fs_val3":"'+xmlText(E017_fs,'fs_val3',2)+'", "fs_val6":"'+xmlText(E017_fs,'fs_val6',2)+'", "fs_val8":"'+xmlText(E017_fs,'fs_val8',2)+'", "fs_val9":"'+xmlText(E017_fs,'fs_val9',2)+'", "fs_val10":"'+xmlText(E017_fs,'fs_val10',2)+'", "fs_val16":"'+xmlText(E017_fs,'fs_val16',2)+'", "fs_acct_dt":"'+xmlText(E017_fs,'fs_acct_dt',6)+'" },' +
				'{ "fs_val3":"'+xmlText(E017_fs,'fs1_val3',2)+'", "fs_val6":"'+xmlText(E017_fs,'fs1_val6',2)+'", "fs_val8":"'+xmlText(E017_fs,'fs1_val8',2)+'", "fs_val9":"'+xmlText(E017_fs,'fs1_val9',2)+'", "fs_val10":"'+xmlText(E017_fs,'fs1_val10',2)+'", "fs_val16":"'+xmlText(E017_fs,'fs1_val16',2)+'", "fs_acct_dt":"'+xmlText(E017_fs,'fs1_acct_dt',6)+'" },' +
				'{ "fs_val3":"'+xmlText(E017_fs,'fs2_val3',2)+'", "fs_val6":"'+xmlText(E017_fs,'fs2_val6',2)+'", "fs_val8":"'+xmlText(E017_fs,'fs2_val8',2)+'", "fs_val9":"'+xmlText(E017_fs,'fs2_val9',2)+'", "fs_val10":"'+xmlText(E017_fs,'fs2_val10',2)+'", "fs_val16":"'+xmlText(E017_fs,'fs2_val16',2)+'", "fs_acct_dt":"'+xmlText(E017_fs,'fs2_acct_dt',6)+'" }' +
				
			']';
			}
			fs_summ_json = JSON.parse(text);

			// clog("fs_acct_dt="+fs_summ_json[0].fs_acct_dt+" fs_val3="+fs_summ_json[0].fs_val3);
			// clog("fs_acct_dt="+fs_summ_json[1].fs_acct_dt+" fs_val3="+fs_summ_json[1].fs_val3);
			// clog("fs_acct_dt="+fs_summ_json.fs_val[1].fs_acct_dt+" fs_val3="+fs_summ_json.fs_val[1].fs_val3);
			// clog("fs_acct_dt="+fs_summ_json.fs_val[2].fs_acct_dt+" fs_val3="+fs_summ_json.fs_val[2].fs_val3);
			// clog(text);

		}

var maxvalue;
var stepvalue;
 
function getMaxno(data) {
	max = 0;
	for (i=0;i<data.length ; i++)
	{
		if (eval(data[i]['fs_val3']) > max) {
			max = data[i]['fs_val3'];
			maxindex = i;
		}
		if (eval(data[i]['fs_val9']) > max) {
			max = data[i]['fs_val9'];
			maxindex = i;
		}
		//console.log ("data[i]['cnt']="+data[i]['cnt']+" max="+max);
		//if (i % 5 != 0 ) data[i]['fs_acct_dt'] = '';
		
	}
	clog("max="+max);
	if (max<1000) {		// 1000
		maxvalue = Math.ceil(max/100)*100;
		stepvalue = maxvalue/5;
	}
	else if (max<10000) {	// 1만
		maxvalue = Math.ceil(max/1000)*1000;
		stepvalue = maxvalue/5;
	}
	else if (max<100000) {		// 10만
		maxvalue = Math.ceil(max/10000)*10000;
		stepvalue = maxvalue/5;
	}
	else if (max<250000) {		// 25만
		maxvalue = Math.ceil(max/25000)*25000;
		stepvalue = maxvalue/5;
	}
	else if (max<500000) {		// 50만
		maxvalue = Math.ceil(max/50000)*50000;
		stepvalue = maxvalue/5;
	}
	else if (max<1000000) {	// 100만
		maxvalue = Math.ceil(max/100000)*100000;
		stepvalue = maxvalue/5;
	}
	else if (max<10000000) {		// 1000만
		maxvalue = Math.ceil(max/1000000)*1000000;
		stepvalue = maxvalue/5;
	}
	else if (max<100000000) {	// 1억
		maxvalue = Math.ceil(max/10000000)*10000000;
		stepvalue = maxvalue/5;
	}
	else if (max<1000000000) {	// 10억
		maxvalue = Math.ceil(max/100000000)*100000000;
		stepvalue = maxvalue/5;
	}
	else if (max<10000000000) { // 100억
		maxvalue = Math.ceil(max/1000000000)*1000000000;
		stepvalue = maxvalue/5;
	}
	else if (max<100000000000) { // 1000억 219,021,356,989
		maxvalue = Math.ceil(max/10000000000)*10000000000;
		stepvalue = maxvalue/5;
	}			 
	else if (max<1000000000000) { // 1조
		maxvalue = Math.ceil(max/100000000000)*100000000000;
		stepvalue = maxvalue/5;
	}
	else if (max<10000000000000) { // 10조
		maxvalue = Math.ceil(max/1000000000000)*1000000000000;
		stepvalue = maxvalue/5;
	}			 
	else if (max<100000000000000) { // 100조 
		maxvalue = Math.ceil(max/10000000000000)*10000000000000;
		stepvalue = maxvalue/5;
	}
	else if (max<1000000000000000) { // 1000조
		maxvalue = Math.ceil(max/100000000000000)*100000000000000;
		stepvalue = maxvalue/5;
	}
	return max; 
}

function mydrawChart(E017) {
	//console.log('drawChart');
	E017_fs = E017.getElementsByTagName('fs_summ')[0];
	chartbox = document.getElementById("chartbox"); 
	chartbox.innerHTML = '';
	max = getMaxno(fs_summ_json);
	if (eval(E017_fs.getElementsByTagName('fs_val3')[0].textContent) > 10000000) {
		xx = '금액(10억)';
	} else xx= '금액(천원)';
	half = maxvalue/2;
	console.log('maxvalue='+maxvalue+' stepvalue='+stepvalue);
	//myLineChart.clearAll();
	myLineChart =  new dhtmlXChart({
		view:"bar",
		container:"chartbox",
		value:"#fs_val3#",
		label:"#fs_val3#",
			
		color:"#1293f8",
		
		item:{
			borderColor: "#1293f8",
			color:"#E9602C", 
			template:function(obj){
				return number_format(obj) 
			}
		},
		line:{
			color:"#1293f8",
			width:3
		},
		xAxis:{
			title:"<span style='font: normal bold small 궁서 ; color: #1293f8;'>자산총계</span><span style='font: normal bold small 궁서 ; color: #E96060;'> 부채총계</span><span style='font: normal bold small 궁서 ; color: #66CC00;'> 자본총계</span><span style='font: normal bold small 궁서 ; color: #F060F0;'> 매출액</span><span style='font: normal bold small 궁서 ; color: #CC7B55;'> 매출총이익</span><span style='font: normal bold small 궁서 ; color: #557bCC;'> 당기순이익</span>",
			template:"#fs_acct_dt#"
		},
		offset:0,
		yAxis:{
			start:0,
			end:maxvalue,
			step:stepvalue,
			title:"<span style='font: normal bold small 궁서 ; color: #E9602C;'>"+xx+"</span>",
			template:function(obj){
				return number_format(obj) //(obj%20?"":obj)
			}
		},
		padding:{
				left:80,
				bottom: 50
			}
	});
	
	//myLineChart.setNumberFormat("0,000",0,".",",");
	// 부채총계
	myLineChart.addSeries({
     view: "bar",
     item:{  borderColor: "#1293f8",radius:3 ,color:"#E96060", template:function(obj){
				return number_format(obj) //(obj%20?"":obj)
			}},
     line:{  color:"#E96060" },
	color:"#E96060",
     value:"#fs_val6#",
	 label:"#fs_val6#"
	});
	// 자본총계
	myLineChart.addSeries({
     view: "bar",
     item:{  borderColor: "#1293f8",radius:3 ,color:"#66CC00", template:function(obj){
				return number_format(obj) //(obj%20?"":obj)
			}},
     line:{  color:"#66CC00" },
	color:"#66CC00",
     value:"#fs_val8#",
	 label:"#fs_val8#"
	});

	// 매출액
	myLineChart.addSeries({
     view: "bar",
     item:{  borderColor: "#1293f8",radius:3 ,color:"#F060F0", template:function(fs_summ_json){
				return number_format(fs_summ_json) //(obj%20?"":obj)
			}},
     line:{  color:"#F060F0" },
	color:"#F060F0",
     value:"#fs_val9#",
	 label:"#fs_val9#"
	});

	// 매출총이익
	myLineChart.addSeries({
     view: "bar",
     item:{  borderColor: "#1293f8",radius:3 ,color:"#CC7B55", template:function(fs_summ_json){
				return number_format(fs_summ_json) //(obj%20?"":obj)
			}},
     line:{  color:"#CC7B55" },
	color:"#CC7B55",
     value:"#fs_val10#",
	 label:"#fs_val10#"
	});

	// 당기순이익
	myLineChart.addSeries({
     view: "bar",
     item:{  borderColor: "#1293f8",radius:3 ,color:"#557bCC", template:function(fs_summ_json){
				return number_format(fs_summ_json) //(obj%20?"":obj)
			}},
     line:{  color:"#557bCC" },
	color:"#557bCC",
     value:"#fs_val16#",
	 label:"#fs_val16#"
	});

	myLineChart.parse(fs_summ_json,"json");
	myLineChart.attachEvent("onItemClick", function(id){
		
    return true;
})
}

var maxindex=10;
var maxvalue=100;
var stepvalue=0;
var tr1=75;
var tr2=99;
var data = [
    { sales:3.8, year:"2001" },
    { sales:3.4, year:"2002" },

    { sales:4.8, year:"2009" }
];