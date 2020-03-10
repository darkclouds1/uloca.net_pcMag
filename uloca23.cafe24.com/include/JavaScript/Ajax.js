	//var xhr;

// mjAjax ------------------------------------------------------------------------------------------------------

if(!window["mjAjax"]){
	
	mjAjax = new function(){
		
		var h = {};
		
		this.hub = h; 
		h.implementer = "http://blueoceans.com"; 
		h.implVersion = "0.1";
		h.specVersion = "0.1";
		h.author = "HMJ";
		h.implExtraData = {};
		h.style="";
		var libs = {};
		h.libraries = libs;
		h.clntsub = "";
			
		this.createXHR =	function () {
			var xhr;
			if (window.XMLHttpRequest)
			{
				xmlHttp = new XMLHttpRequest();
				this.hub.style="XMLHttpRequest";
			}
			else {
				try {
					xhr = new ActiveXObject("Msxml2.XMLHTTP");
					this.hub.style="Msxml2.XMLHTTP";
				} catch (e) {
					try {
						xhr = new ActiveXObject("Microsoft.XMLHTTP");
						this.hub.style="Microsoft.XMLHTTP";
					} catch (E) {
						xhr = false;
					}
				}
			}
			/*if (!xhr && typeof XMLHttpRequest != 'undefined') {
				  xhr = new XMLHttpRequest();
			} */
			return xhr;
		};

		this.callAjax = function (server,client) {
			//var xhr;
				//if (!xhr || typeof xhr == "undefined" )
				//alert("callAjax");
				xhr = this.createXHR();
				
				xhr.onreadystatechange=client;
				xhr.open("GET", server);
				xhr.send(null);
				//return xhr;
			};
		this.callAjax1 = function (server,client) {

				xhr = this.createXHR();
				this.hub.clntsub=client;
				xhr.onreadystatechange=client01;
				xhr.open("GET", server);
				xhr.send(null);
				//return xhr;
			};


	this.callAjaxPost = function (server,client,parm) {
			
				xhr = this.createXHR();
				
				xhr.onreadystatechange=client;
				xhr.open("POST", server, true);
				xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=euc-kr"); 
				xhr.send(parm);
				//return xhr;
			};
	this.callAjaxPostUTF8 = function (server,client,parm) {
			
				xhr = this.createXHR();
				
				xhr.onreadystatechange=client;
				xhr.open("POST", server, true);
				xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8"); 
				xhr.send(parm);
				//return xhr;
			};
	this.callAjaxPost1 = function (server,client,parm) {
				xhr = this.createXHR();
				this.hub.clntsub=client;
				xhr.onreadystatechange=client01;
				xhr.open("POST", server, true);
				xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=euc-kr"); 
				xhr.send(parm);
				// var parm="name="+namevalue+"&age="+agevalue
				//return xhr;
			}; 

	
	} // end mjAjax = new function(){

	
} // end if(!window["mjAjax"]){
// mjAjax ------------------------------------------------------------------------------------------------------
	

	function client01() {
		try {
			if (xhr.readyState == 4) {
				if (xhr.status == 200) {
					window["mjAjax"].hub.clntsub(xhr.responseText);
					
				} else {
					alert ("Error" + xhr.status);
				}
			}
		} catch (e) {}
		
	}

	function mjVersion() {
		//alert("mjVersion");
		try {
			if (xhr.readyState == 4) {
				if (xhr.status == 200) {
					mjAjax.hub.author=xhr.responseText;
					alert(mjAjax.hub.author);
				} else {
					alert ("Error in mjVersion " + xhr.status);
				}
			}
		} catch (e) {}
	}

	function chkVersion() {
		//alert("chkVersion");
		svr="http://oceans.javasarang.net/Ajax/chkVersion.jsp?p=sr" ;
		mjAjax.callAjax1(svr,mjVersion);
	}
	//chkVersion();

// this.callAjax2,this.client0 미완성....