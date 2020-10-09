<?php 
include 'vendor/autoload.php';


$dados = array(
    array('nome' => 'ADAO CARLOS', 'cpf' => '68734816615'),
    array('nome' => 'CARLOS EDUARDO DO PRADO', 'cpf' => '04438635632'),
    array('nome' => 'ALEX JUNIO FERREIRA COELHO', 'cpf' => '...')
);

$mensagem = '.';



// Parse pdf file and build necessary objects.
$parser = new \Smalot\PdfParser\Parser();



//verificar se formulário foi enviado
if(isset($_POST['enviar-formulario'])):
    $formatosPermitidos = array("pdf");
    $quantidadeArquivos = count($_FILES['arquivos']['name']);
    $contador = 0;
    //loop de total de arquivos

    while($contador < $quantidadeArquivos):

    $extensao = pathinfo($_FILES['arquivos']['name'][$contador], PATHINFO_EXTENSION);

    if(in_array($extensao, $formatosPermitidos)):
        $pasta = "arquivos/";
        $temporario = $_FILES['arquivos']['tmp_name'][$contador];
        $novoNome = uniqid().".$extensao";


        $pdf    = $parser->parseFile($temporario);
        $text = $pdf->getText();
       echo $text =  utf8_encode($text);

        foreach($dados as $cooperado){

            if(strpos($text, $cooperado['cpf'])){
                echo 'Pesquisa por CPF ok: '.$dados['cpf']."<br>";
                if(move_uploaded_file($temporario, $pasta.$novoNome)):
                    $mensagem = "Upload feito com sucesso!";
                 else:
                     $mensagem = "Erro, não foi possível fazer o upload";
                 endif;
            } else {
            
                $cpf = $cooperado['cpf'];
            
                $bloco_1 = substr($cpf,0,3);
                $bloco_2 = substr($cpf,3,3);
                $bloco_3 = substr($cpf,6,3);
                $dig_verificador = substr($cpf,-2);
                $cpf_formatado = $bloco_1.".".$bloco_2.".".$bloco_3."-".$dig_verificador;
            
                if(strpos($text, $cpf_formatado)){
                    echo 'Pesquisa por CPF FORMATADO ok: '.$cpf_formatado."<br>";
                    if(move_uploaded_file($temporario, $pasta.$novoNome)):
                        $mensagem = "Upload feito com sucesso!";
                     else:
                         $mensagem = "Erro, não foi possível fazer o upload";
                     endif;
                } else {
            
                    if(strpos($text, $cooperado['nome'])){
                        echo 'Pesquisa por nome ok: '.$dados['nome']."<br>";
                        if(move_uploaded_file($temporario, $pasta.$novoNome)):
                            $mensagem = "Upload feito com sucesso!";
                         else:
                             $mensagem = "Erro, não foi possível fazer o upload";
                         endif;
                    } else {
                        $mensagem =  'Não encontramos dados suficientes nesse arquivo.';
                    }
            
            
                }
               
            
            }

        }

        
    else:

        $mensagem = "Formato Inválido";

    endif;
    echo $mensagem."<br>";
    ++$contador;
    endwhile;

endif;











echo "<br>-------------------------------------<br>";



/*
// Retrieve all pages from the pdf file.
$pages  = $pdf->getPages();
 
// Loop over each page to extract text.
foreach ($pages as $page) {
    echo $page->getText();
}*/