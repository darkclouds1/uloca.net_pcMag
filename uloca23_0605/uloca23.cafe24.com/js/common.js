function clog(msg) {
	console.log(msg);
}
function number_format(amount) {

	if(amount==0) return 0;
 
    var reg = /(^[+-]?\d+)(\d{3})/;
    var n = (amount + '');
 
    while (reg.test(n)) n = n.replace(reg, '$1' + ',' + '$2');
 
    return n;
}

function dateAddDel(sDate, nNum, type) {
    var yy = eval(sDate.substr(0, 4)); //, 10);
    var mm = eval(sDate.substr(5, 2)); //parseInt(sDate.substr(5, 2), 10);
    var dd = eval(sDate.substr(8)); //parseInt(sDate.substr(8), 10);
    var d = new Date();
    if (type == "d") {
        d = new Date(yy, mm - 1, dd + nNum);
    }
    else if (type == "w") {
		nNum = 7 * nNum;
        d = new Date(yy, mm - 1 , dd + nNum);
    }
	else if (type == "m") {
        d = new Date(yy, mm - 1 + nNum, dd);
    }
    else if (type == "y") {
        d = new Date(yy + nNum, mm - 1, dd);
    }
 
    yy = d.getFullYear();
    mm = d.getMonth() + 1; mm = (mm < 10) ? '0' + mm : mm;
    dd = d.getDate(); dd = (dd < 10) ? '0' + dd : dd;
 
    return '' + yy + '-' +  mm  + '-' + dd;
}

function setDate(sDate,sep) {
	if (sDate.length<5) return sDate;

	if (sDate.length == 6)
	{
		return sDate.substr(0,2)+sep+sDate.substr(2,2)+sep+sDate.substr(4,2);
	} else return sDate.substr(0,4)+sep+sDate.substr(4,2)+sep+sDate.substr(6,2);
}

function getToday(sep) {
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
	var yyyy = today.getFullYear();

	if(dd<10) {
		dd = '0'+dd;
	} 

	if(mm<10) {
		mm = '0'+mm;
	} 

	today = yyyy + sep + mm + sep + dd  ;
	return today;
}

function getTodayTime(sep) {
	var sep2 = ':';
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
	var yyyy = today.getFullYear();
	var hh = today.getHours();
	var mi = today.getMinutes();
	var ss = today.getSeconds();
	
	if(dd<10) {
		dd = '0'+dd;
	} 

	if(mm<10) {
		mm = '0'+mm;
	} 
	if(hh<10) {
		hh = '0'+hh;
	} 

	if(mi<10) {
		mi = '0'+mi;
	}
	if(ss<10) {
		ss = '0'+ss;
	}

	today = yyyy + sep + mm + sep + dd + ' ' + hh + sep2 + mi + sep2 + ss;
	return today;
}

/* --------------------------------------------------------------------------------------------------------------------------------------------------
기능 : object 내용보기
-------------------------------------------------------------------------------------------------------------------------------------------------- */
function viewObject(obj) {
	try
	{
		var txtValue;
		for(var x in obj) { txtValue += [x, obj[x]]+"\n"; }
		console.log("viewObject " +txtValue);
	} catch (e) {}
	
	try
	{
		txtValue = "";
		for(var x in obj.target) { txtValue += [x, obj[x]]+"\n"; }
		console.log("viewObject target " +txtValue);
	} catch (e) {}

	try
	{
		txtValue = "";
		for(var x in obj.currentTarget) { txtValue += [x, obj[x]]+"\n"; }
		console.log("viewObject currentTarget " +txtValue);
	} catch (e) {}

	try
	{
		txtValue = "";
		for(var x in obj.delegateTarget) { txtValue += [x, obj[x]]+"\n"; }
		console.log("viewObject delegateTarget " +txtValue);
	} catch (e) {}

}

/* -------------------------------------------------------------------------
	new Version of get ajax : 20180728 HMJ
-------------------------------------------------------------------------- */
function getAjax(server,client) {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
     ///document.getElementById("demo").innerHTML = this.responseText;
	 client(this.responseText);
    } else if (this.status == 504)
    {
		move_stop();
		alert('Time-out Error...');
		return;
    } else {
		//alert ("Error" + xhttp.status);
	}
  };
  xhttp.open("GET", server, true);
  xhttp.send();
}
function getAjaxPost(server,client,parm) {
	var xhr = new XMLHttpRequest();
				
	xhr.onreadystatechange=function() {
		if (this.readyState == 4 && this.status == 200) {
		 client(this.responseText);
		} else if (this.status == 504)
		{
			alert('Time-out Error...');
			return;
		} else {
			//alert ("Error" + xhttp.status);
		}
	  };
	xhr.open("POST", server, true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8"); 
	xhr.send(parm);

	// xhttp.send("fname=Henry&lname=Ford");
}