<?php

echo '<pre>';
$file = fopen("./Set_pergunta.csv", "r");
	while(!feof($file)){
		$linha=fgets($file);
		$linha=utf8_encode($linha);
		$exploded = explode(";",$linha);
		
		
		$Id_Pergunta =  trim($exploded['0']);
		$pergunra =  trim($exploded['1']);
		$Destino_Sim =  trim($exploded['2']);
		$Destino_Nao =  trim($exploded['3']);
		
		
	
		
		
		
	print_r($exploded);
		
		
		
	//echo $sql = trim("INSERT INTO [dbo].[tabela_pos_perguntas] VALUES($Id_Pergunta,'$pergunra',$Destino_Sim,$Destino_Nao);").'<br>';
	/*	
	
		if(!sqlsrv_query($conn, $sql)){
			echo $sql.'<br>';
		}
	*/
	}


    
?>