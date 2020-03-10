//				<script type="text/javascript"> 
//<![CDATA[ 
function to_more(arg){ 
	var currentPageNo = Number(document.getElementById("currentPageNo").value) + arg; 
	var maxPageViewNoByWshan = Number(document.getElementById("maxPageViewNoByWshan").value) + 1; 
	var currListUrl = "/ep/tbid/tbidList.do?area=&bidNm=&bidSearchType=1&fromBidDt=2018%2F05%2F19&fromOpenBidDt=&instNm=&radOrgan=1&regYn=Y&searchDtType=1&searchType=1&taskClCds=&toBidDt=2018%2F06%2F18&toOpenBidDt="; 
	var mainUrl = currListUrl.substr(0, currListUrl.indexOf("?")+1); 
	var paramUrl = currListUrl.substr(currListUrl.indexOf("?")+1); 
	var reqArry = paramUrl.split("&"); 
	var nextUrl = mainUrl; 
	 
	for(var i = 0 ; i < reqArry.length ; i++){ 
		if(reqArry[i].indexOf("currentPageNo") >= 0){ 
			nextUrl += "currentPageNo="+currentPageNo + "&"; 
		} else if(reqArry[i].indexOf("maxPageViewNoByWshan") >= 0){ 
			nextUrl += "maxPageViewNoByWshan="+maxPageViewNoByWshan + "&"; 
		} else { 
			nextUrl += reqArry[i]+"&"; 
		} 
	}
	
	if(nextUrl.indexOf("currentPageNo") < 0){ 
		nextUrl += "currentPageNo=" + currentPageNo + "&"; 
	} 
	if(nextUrl.indexOf("maxPageViewNoByWshan") < 0){ 
		nextUrl += "maxPageViewNoByWshan=" + maxPageViewNoByWshan + "&"; 
	} 
	 
	window.location.href = nextUrl; 
} 
function to_page(arg){ 
	var currentPageNo = arg; 
	var maxPageViewNoByWshan = Number(document.getElementById("maxPageViewNoByWshan").value); 
	var currListUrl = "/ep/tbid/tbidList.do?area=&bidNm=&bidSearchType=1&fromBidDt=2018%2F05%2F19&fromOpenBidDt=&instNm=&radOrgan=1&regYn=Y&searchDtType=1&searchType=1&taskClCds=&toBidDt=2018%2F06%2F18&toOpenBidDt="; 
	var mainUrl = currListUrl.substr(0, currListUrl.indexOf("?")+1); 
	var paramUrl = currListUrl.substr(currListUrl.indexOf("?")+1); 
	var reqArry = paramUrl.split("&"); 
	var nextUrl = mainUrl; 
	 
	for(var i = 0 ; i < reqArry.length ; i++){ 
		if(reqArry[i].indexOf("currentPageNo") >= 0){ 
			nextUrl += "currentPageNo="+currentPageNo + "&"; 
		} else if(reqArry[i].indexOf("maxPageViewNoByWshan") >= 0){ 
			nextUrl += "maxPageViewNoByWshan="+maxPageViewNoByWshan + "&"; 
		} else { 
			nextUrl += reqArry[i]+"&"; 
		} 
	} 
	 
	if(nextUrl.indexOf("currentPageNo") < 0){ 
		nextUrl += "currentPageNo=" + currentPageNo + "&"; 
	} 
	if(nextUrl.indexOf("maxPageViewNoByWshan") < 0){ 
		nextUrl += "maxPageViewNoByWshan=" + maxPageViewNoByWshan + "&"; 
	} 
	 
	window.location.href = nextUrl; 
} 
//]]> 
//</script> 