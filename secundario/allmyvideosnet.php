<?php

/*

Por GET y luego por POST

http://played.to/49qyhpnfhgip

http://89.238.150.210:8777/i/01/00655/p769fztivvp3.jpg

http://89.238.150.210:8777/repynsknbsie2cbd4oq3tex3lnwvammyu6aslyky4y7n7sflz2plxumqga/v.mp4.flv?start=0

A veces se cuela un mp4 llamado http://allmyvideos.net/ph.mp4



BLOQUEADO POR IP.

Usan un flash que cargue el html por POST ya que el flash podría cargarlo gracias al crossdomain
http://played.to/crossdomain.xml

Flash hecho, solo falta implementarlo


ES NECESARIO CAMBIAR EL SWF A UN IFRAME QUE NO ENVIE REFERER, COMO CON LOS BOTONES


CON PONER EN EL REFERER http://played.to/ ya funciona.



iframe.src = 'javascript:\'<embed src="/util/fla/f/http://played.to/1234" name="descargador_archivos"></embed><script>function lanzaPlayedTo(){	if(typeof DESCARGADOR_ARCHIVOS_SWF === "undefined"){	    var d = setTimeout(lanzaPlayedTo, 200);}else if(DESCARGADOR_ARCHIVOS_SWF === true){        descargador_archivos.CargaWeb(            {"url":"http://played.to/49qyhpnfhgip",            "metodo":"GET"},            "procesaPlayedTo1");     }}function procesaPlayedTo1(txt){var regex = /<input.*?name="(.*?)".*?value="(.*?)".*?>/ig;var postA = [];var res = [];while((res = regex.exec(txt)) != null){    if(res[1] === "referer")res[2] = "";    postA.push(res[1] + "=" + res[2].split(" ").join("+"));}console.log(postA);var post = postA.join("&");	console.log(post);	descargador_archivos.CargaWeb({		"url":"http://played.to/49qyhpnfhgip",		"metodo":"POST",		"post":post	}, "procesaPlayedTo2");}function procesaPlayedTo2(txt){    console.log(txt);}        lanzaPlayedTo();</script>\'';


*/

class Allmyvideosnet extends cadena{

function calcula(){
	if(!preg_match('#<form name="F1" method="POST" action=\'\'>#i', $this->web_descargada)){
		setErrorWebIntera('No se encuentra ningún vídeo');
		return;
	}
	
	$id = substr($this->web, strposF($this->web, 'allmyvideos.net/'));
	dbug('id = '.$id);
	
	$web_embedPlayedTo = 'http://allmyvideos.net/'.$id;
	
	$retfull = CargaWebCurl($web_embedPlayedTo,'',array('referer'=>'http://web.com'));
	if (enString($retfull, '"image" : "')) {
		$mode = 'lanzaAllMyVideosNet2';
		$imagen = entre1y2($retfull, '"image" : "', '"');
	} else {
		$imagen = '';
		$web_embedPlayedTo = 'http://allmyvideos.net/'.$id;
		$mode = 'lanzaAllMyVideosNet1';
	}
	
	if(enString($this->web_descargada, 'filename=')){
		$titulo = entre1y2($this->web_descargada, 'filename=', '"');
		if(enString($titulo, '&')){
			$titulo = substr($titulo, 0, strpos($titulo, '&'));
		}
		$titulo = urldecode($titulo);
	} else {
		$titulo = 'AllMyVideos ID: '.$id;
	}

	// FALLA EN EL CALLBACK DEL SWF. EDITAR EL SWF
	
	$urlJS = 
	
	'function lanzaAllMyVideosNet(){'.
		'if(typeof DESCARGADOR_ARCHIVOS_SWF === "undefined"){'.
			'setTimeout(lanzaAllMyVideosNet, 200)'.
		'}'.
		'else if(DESCARGADOR_ARCHIVOS_SWF === true){'.
			'getFlashMovie("descargador_archivos").CargaWeb({'.
				'"url":"'.$web_embedPlayedTo.'",'.
				'"metodo":"GET"'.
			'}, "'.$mode.'");'.
		'}'.
	'}'.
	'function lanzaAllMyVideosNet1(txt){'.
		'var regex = /<input.*?name="(.*?)".*?value="(.*?)".*?>/ig;'.
		
		'var post = "";'.
		'var res = [];'.
		'while((res = regex.exec(txt)) != null){'.
			'if(res[1] === "referer")res[2] = "";'.
			'post += res[1] + "=" + res[2] +"&";'.
		'}'.
		
		'getFlashMovie("descargador_archivos").CargaWeb({'.
			'"url":"'.$web_embedPlayedTo.'",'.
			'"metodo":"POST",'.
			'"post":post'.
		'}, "lanzaAllMyVideosNet2");'.
	'}'.
	
	'function lanzaAllMyVideosNet2(txt){'.
		'D.g("imagen_res").src = txt.split("\"image\" : \"")[1].split("\"")[0];'.
		
		'if(txt.indexOf(".setup(") !== -1){'.
			'txt = txt.substr(txt.indexOf(".setup("));'.
		'}'.
		'var urls = txt.split("\"sources\" : ")[1].split("]")[0]+"]";'.
		
		'var urls = JSON.parse(urls);'.
		
		'urls.sort(function(a,b){return parseInt(a["label"])<parseInt(b["label"])});'.
		
		'url = urls[0]["file"];'.
		//'console.log(url);'.
		'mostrarResultado(url);'.
	'}'.
	
	
	'function mostrarResultado(entrada){'.
		'finalizar(entrada,"Descargar");'.
	'}'.
	
	'function mostrarFallo(){'.
		'finalizar("","Ha ocurrido un error");'.
	'}'.
	
	'if(typeof descargador_archivos === "undefined"){'.
		'descargador_archivos = genera_swf_object("/util/fla/f/allmyvideos.net");'.
	'}'.
	
	'lanzaAllMyVideosNet();';
	
	$obtenido=array(
		'titulo'  => $titulo,
		'imagen'  => $imagen,
		'enlaces' => array(
			array(
				'url'  => $urlJS,
				'tipo' => 'jsFlash'
			)
		)
	);
	
	finalCadena($obtenido);
}

}
