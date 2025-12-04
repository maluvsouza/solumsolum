<?php

/**
 * Função auxiliar para fazer upload de arquivo
 * 
 * @param array $file - Array $_FILES['campo']
 * @param string $destino - Caminho de destino sem barra final
 * @param string $nomeArquivo - Nome do arquivo (opcional, gera um se não fornecido)
 * @param array $tiposPermitidos - Array com tipos MIME permitidos
 * @param int $tamanhoMaximo - Tamanho máximo em bytes
 * @return array - ['sucesso' => bool, 'arquivo' => string_nome_arquivo, 'erro' => string_mensagem_erro]
 */
function uploadArquivo($file, $destino, $nomeArquivo = null, $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'], $tamanhoMaximo = 5242880) {
    
    // Validações básicas
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['sucesso' => false, 'arquivo' => null, 'erro' => 'Nenhum arquivo foi enviado'];
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $erros = [
            UPLOAD_ERR_INI_SIZE => 'O arquivo excede o tamanho máximo permitido pelo servidor',
            UPLOAD_ERR_FORM_SIZE => 'O arquivo excede o tamanho máximo do formulário',
            UPLOAD_ERR_PARTIAL => 'O arquivo foi parcialmente enviado',
            UPLOAD_ERR_NO_TMP_DIR => 'Pasta temporária não encontrada',
            UPLOAD_ERR_CANT_WRITE => 'Falha ao escrever o arquivo',
            UPLOAD_ERR_EXTENSION => 'Extensão não permitida'
        ];
        return ['sucesso' => false, 'arquivo' => null, 'erro' => $erros[$file['error']] ?? 'Erro desconhecido'];
    }

    // Valida tipo MIME
    $tipoMime = mime_content_type($file['tmp_name']);
    if (!in_array($tipoMime, $tiposPermitidos)) {
        return ['sucesso' => false, 'arquivo' => null, 'erro' => 'Tipo de arquivo não permitido'];
    }

    // Valida tamanho
    if ($file['size'] > $tamanhoMaximo) {
        return ['sucesso' => false, 'arquivo' => null, 'erro' => 'Arquivo muito grande (máximo ' . ($tamanhoMaximo / 1048576) . 'MB)'];
    }

    // Cria diretório se não existir
    if (!is_dir($destino)) {
        mkdir($destino, 0755, true);
    }

    // Gera nome do arquivo
    if (!$nomeArquivo) {
        $extensao = pathinfo($file['name'], PATHINFO_EXTENSION);
        $nomeArquivo = uniqid() . '_' . time() . '.' . $extensao;
    }

    $caminhoCompleto = $destino . '/' . $nomeArquivo;

    // Faz o upload
    if (move_uploaded_file($file['tmp_name'], $caminhoCompleto)) {
        return ['sucesso' => true, 'arquivo' => $nomeArquivo, 'erro' => null];
    } else {
        return ['sucesso' => false, 'arquivo' => null, 'erro' => 'Falha ao mover o arquivo para o destino'];
    }
}

?>
