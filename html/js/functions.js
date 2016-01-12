	var arrayLanguages = new Array(4);
	arrayLanguages[0] = "es";
	arrayLanguages[1] = "ca";
	arrayLanguages[2] = "fr";
	arrayLanguages[3] = "en";
	
function changeLanguage(lang){ 
	//alert("lang: "+lang);
	if (manualcontent!=null) {
		fillManualContent(lang,manualcontent)
	}
	for(i=0;i<4;i++){
		//alert("i: "+i + " arrayLanguages[i] " + arrayLanguages[i]);
		var arrayNodosFamilia = $$("span."+arrayLanguages[i]);
		//alert("arrayNodosFamilia: " + arrayNodosFamilia);
		arrayNodosFamilia.each( function(el, indice){
				//alert("el: "+el);
				if (lang == arrayLanguages[i]){
					//el.style.visibility= 'visible';
					el.style.display = 'block';
				}else{
					//el.style.visibility= 'hidden';
					el.style.display = 'none';
				}
			}
		);
	} 
}

	var arrayTabs = new Array(2);
	arrayTabs[0] = "directory";
	arrayTabs[1] = "manual";

	var actualTab; 

function selectTab(tabSelected){
	if (actualTab!=null){
		  $(actualTab).setStyle({border: 'none', borderBottom: '2px solid black', color: 'blue'});


	}

	actualTab = tabSelected + 'title';

        $(actualTab).setStyle({	border: '2px solid black', borderBottom: 'none', color: 'black' });
	
	for(i=0;i<2;i++){
		if (tabSelected == arrayTabs[i]){
			$(arrayTabs[i]).style.display = 'block';
		}else{
			$(arrayTabs[i]).style.display = 'none';
		}
	} 	
}

var manualcontent;
function fillManualContent(lang,file) {
	var url = './src/timetable/lang/'+lang+ '_utf8/help/timetable/' + file; 
	manualcontent = file;
	/*var url = 'checkZip.cfm';*/
	/*var params = 'zip=' + $F('zip');*/
	var ajax = new Ajax.Updater(
		{success: 'manualcontent'},
		url,
		{method: 'get', onFailure: reportError});
}

function reportError(request) {
	$('manualcontent').update('Error on load page');
}

function CridaAVisita(){
	//alert("Ahi voy!");
	new Ajax.Request("../visita/visita.php", {
	method: 'GET',
	onSuccess : function(resp) { 
		var resposta = resp.responseXML;
		//alert(resposta.getElementsByTagName("ID")[0].childNodes[0].nodeValue + resposta.getElementsByTagName("VALUE")[0].childNodes[0].nodeValue);
	
		$('counter').update(resposta.getElementsByTagName("VALUE")[0].childNodes[0].nodeValue);
	}, 
	onFailure : function(resp) {
		$('counter').update('ERROR retrieving counter');
	},
	parameters : 'id=TTB'
	}); 
}

