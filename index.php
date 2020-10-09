<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir vários arquivos</title>
</head>
<body>

<?php

if(isset($_POST['enviar-formulario'])):
    $formatosPermitidos = array("pdf");
    $quantidadeArquivos = count($_FILES['arquivos']['name']);
    $contador = 0;
    while($contador < $quantidadeArquivos):

    $extensao = pathinfo($_FILES['arquivos']['name'][$contador], PATHINFO_EXTENSION);

    if(in_array($extensao, $formatosPermitidos)):
        $pasta = "arquivos/";
        $temporario = $_FILES['arquivos']['tmp_name'][$contador];
        $novoNome = uniqid().".$extensao";

        if(move_uploaded_file($temporario, $pasta.$novoNome)):
           echo  $mensagem = "Upload feito com sucesso!";
        else:
            $mensagem = "Erro, não foi possível fazer o upload";
        endif;
    else:
        $mensagem = "Formato Inválido";


    endif;
    echo $mensagem;
    $contador++;
    endwhile;
endif;


?>

<form action="app.php" method="POST" enctype="multipart/form-data">
<input type="file" name="arquivos[]" id="" multiple><br>
<input type="submit" value="Enviar" name="enviar-formulario">
</form>
    
</body>
</html>