<div>
	<span id="id<?php echo $R2['random_id'];?>" class="Descarga2"></span>
	<a class="TV" id="id<?php echo $R2['random_id'];?>2" target="_blank" href="/player/?img=<?php echo $R['url_img_res'];?>"></a>
</div>
<script>
	<?php
		error_reporting(E_ALL);
		if(!preg_match('@{{([a-zA-Z0-9_-]*?)}}@', $R2['dir_resultado'])){
			echo $R2['dir_resultado'];
		}
		else{
			echo preg_replace_callback('@{{([a-zA-Z0-9_-]*?)}}@', function($entrada){global $R2;return $R2[$entrada[1]];}, $R2['dir_resultado']);
		}
	?>
</script>
<script>
	function finalizar<?php echo $R2['random_id'];?>(linkfinal, txtfinal, extension){
		if(!extension)
			extension = "mp4";
		document.getElementById('id<?php echo $R2['random_id'];?>2').setAttribute('href', document.getElementById('id<?php echo $R2['random_id'];?>2').getAttribute('href') + '&ext='+extension+'&video='+encodeURIComponent(linkfinal));
		document.getElementById('id<?php echo $R2['random_id'];?>').innerHTML=ReferrerKiller.linkHtml(
			linkfinal,
			txtfinal,
			{target:'_blank'},
			{verticalAlign:'bottom'},
			'.link{'+
				'background:url("http://www.descargavideos.tv/img/flecha.png") no-repeat scroll 5px 5px #ED8D2D;'+
				'border-radius:10px;'+
				'font-family:Tahoma,Helvetica;'+
				'font-size:16px;'+
				'text-decoration:none;'+
				'line-height:30px;'+
				'word-wrap:break-word;'+
				'text-align:center;'+
				'display:block;'+
				'transition:all 0.3s ease 0s;'+
				'min-height:30px;'+
				'padding:5px 5px 5px 50px;'+
				'color:#F8F8F8;'+
			'}'+
			'.link:hover{'+
				'background-color:#F8F8F8;'+
				'background-position:5px -55px;'+
				'color:#ED8D2D;'+
			'}'
		);
	}
	finalizar = finalizar<?php echo $R2['random_id'];?>;
</script>