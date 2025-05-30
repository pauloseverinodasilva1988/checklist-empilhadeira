<?php
// Função para sanitizar dados de entrada
function sanitizar_input($dados) {
    $dados = trim($dados);
    $dados = stripslashes($dados);
    $dados = htmlspecialchars($dados);
    return $dados;
}

$dataFilePath = 'checklist_data.txt'; // Onde os dados são salvos

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- Lógica de Exportação CSV ---
    if (isset($_POST['exportar']) && $_POST['exportar'] === 'true') {
        if (file_exists($dataFilePath) && filesize($dataFilePath) > 0) {
            $fileContent = file_get_contents($dataFilePath);
            $checklists = json_decode($fileContent, true);

            if (!empty($checklists)) {
                // Define o cabeçalho para download do CSV
                header('Content-Type: text/csv');
                header('Content-Disposition: attachment; filename="relatorio_checklist_empilhadeira.csv"');

                $output = fopen('php://output', 'w');

                // Cabeçalhos do CSV - devem corresponder aos labels no relatório e à ordem de salvamento
                // (e à ordem em que os dados são salvos no array $todos_os_dados abaixo)
                $csvHeaders = [
                    'Data', 'Hora', 'Operador', 'Tipo Empilhadeira', 'Turno', 'Operação',
                    'Pedais', 'Bateria', 'Direcao', 'Buzina', 'Retrovisores', 'Pneus/Rodas',
                    'Assento', 'Cabine', 'Protetor de Carga', 'Freio de Estacionamento',
                    'Painel de Instrumentacao', 'Farois', 'Sinal Sonoro', 'Alavanca de Comandos',
                    'Garfos', 'Corrente', 'Fardil', 'Plataforma do Operador',
                    'Nao Conformidade', 'Detalhes Nao Conformidade'
                ];
                fputcsv($output, $csvHeaders);

                foreach ($checklists as $entry) {
                    // Garantir que a ordem dos dados no CSV corresponda à ordem dos cabeçalhos
                    // e ao que é salvo no JSON. Se um índice não existir, use uma string vazia.
                    $rowData = [];
                    for($i = 0; $i < count($csvHeaders); $i++) {
                        $rowData[] = $entry[$i] ?? '';
                    }
                    fputcsv($output, $rowData);
                }

                fclose($output);
                exit(); // Interrompe a execução após a exportação
            } else {
                echo "<script>alert('Nenhum dado de checklist para exportar.'); window.location.href='checklist_form.html';</script>";
                exit();
            }
        } else {
            echo "<script>alert('Arquivo de dados do checklist não encontrado ou vazio.'); window.location.href='checklist_form.html';</script>";
            exit();
        }
    }

    // --- Lógica de Salvar Checklist (se não for exportação) ---
    $data = sanitizar_input($_POST['data'] ?? '');
    $hora = sanitizar_input($_POST['hora'] ?? '');
    $nome = sanitizar_input($_POST['nome'] ?? '');
    $empilhadeira_tipo = sanitizar_input($_POST['empilhadeira_tipo'] ?? '');
    $turno = sanitizar_input($_POST['turno'] ?? '');
    $operacao = sanitizar_input($_POST['operacao'] ?? '');

    // Inicializa todos os itens do checklist como "Nao Aplicavel"
    // Isso garante que todos os campos existam no array final para consistência,
    // mesmo que não sejam relevantes para um tipo específico de empilhadeira.
    $itens_checklist = [
        'Pedais' => 'Nao Aplicavel',
        'Bateria' => 'Nao Aplicavel',
        'Direcao' => 'Nao Aplicavel',
        'Buzina' => 'Nao Aplicavel',
        'Retrovisores' => 'Nao Aplicavel',
        'Pneus' => 'Nao Aplicavel', // Pneus/Rodas
        'Assento' => 'Nao Aplicavel',
        'Cabine' => 'Nao Aplicavel',
        'Protetor_de_carga' => 'Nao Aplicavel',
        'Freio_de_estacionamento' => 'Nao Aplicavel',
        'Painel_de_instrumentacao' => 'Nao Aplicavel',
        'Farois' => 'Nao Aplicavel',
        'Sinal_Sonoro' => 'Nao Aplicavel',
        'Alavanca_de_comandos' => 'Nao Aplicavel',
        'Garfos' => 'Nao Aplicavel',
        'Corrente' => 'Nao Aplicavel',
        'Fardil' => 'Nao Aplicavel',
        'Plataforma_do_Operador' => 'Nao Aplicavel'
    ];

    // Preenche os itens específicos do tipo de empilhadeira selecionado
    if ($empilhadeira_tipo === 'aGas') {
        $itens_checklist['Pedais'] = sanitizar_input($_POST['aGas_item_Pedais'] ?? 'Nao Aplicavel');
        $itens_checklist['Bateria'] = sanitizar_input($_POST['aGas_item_Bateria'] ?? 'Nao Aplicavel');
        $itens_checklist['Direcao'] = sanitizar_input($_POST['aGas_item_Direcao'] ?? 'Nao Aplicavel');
        $itens_checklist['Buzina'] = sanitizar_input($_POST['aGas_item_Buzina'] ?? 'Nao Aplicavel');
        $itens_checklist['Retrovisores'] = sanitizar_input($_POST['aGas_item_Retrovisores'] ?? 'Nao Aplicavel');
        $itens_checklist['Pneus'] = sanitizar_input($_POST['aGas_item_Pneus'] ?? 'Nao Aplicavel');
        $itens_checklist['Assento'] = sanitizar_input($_POST['aGas_item_Assento'] ?? 'Nao Aplicavel');
        $itens_checklist['Cabine'] = sanitizar_input($_POST['aGas_item_Cabine'] ?? 'Nao Aplicavel');
        $itens_checklist['Protetor_de_carga'] = sanitizar_input($_POST['aGas_item_Protetor_de_carga'] ?? 'Nao Aplicavel');
        $itens_checklist['Freio_de_estacionamento'] = sanitizar_input($_POST['aGas_item_Freio_de_estacionamento'] ?? 'Nao Aplicavel');
        $itens_checklist['Painel_de_instrumentacao'] = sanitizar_input($_POST['aGas_item_Painel_de_instrumentacao'] ?? 'Nao Aplicavel');
        $itens_checklist['Farois'] = sanitizar_input($_POST['aGas_item_Farois'] ?? 'Nao Aplicavel');
        $itens_checklist['Sinal_Sonoro'] = sanitizar_input($_POST['aGas_item_Sinal_Sonoro'] ?? 'Nao Aplicavel');
        $itens_checklist['Alavanca_de_comandos'] = sanitizar_input($_POST['aGas_item_Alavanca_de_comandos'] ?? 'Nao Aplicavel');
        $itens_checklist['Garfos'] = sanitizar_input($_POST['aGas_item_Garfos'] ?? 'Nao Aplicavel');
        $itens_checklist['Corrente'] = sanitizar_input($_POST['aGas_item_Corrente'] ?? 'Nao Aplicavel');
        $itens_checklist['Fardil'] = sanitizar_input($_POST['aGas_item_Fardil'] ?? 'Nao Aplicavel');
    } elseif ($empilhadeira_tipo === 'eletrica') {
        $itens_checklist['Pedais'] = sanitizar_input($_POST['eletrica_item_Pedais'] ?? 'Nao Aplicavel');
        $itens_checklist['Bateria'] = sanitizar_input($_POST['eletrica_item_Bateria'] ?? 'Nao Aplicavel');
        $itens_checklist['Direcao'] = sanitizar_input($_POST['eletrica_item_Direcao'] ?? 'Nao Aplicavel');
        $itens_checklist['Buzina'] = sanitizar_input($_POST['eletrica_item_Buzina'] ?? 'Nao Aplicavel');
        $itens_checklist['Retrovisores'] = sanitizar_input($_POST['eletrica_item_Retrovisores'] ?? 'Nao Aplicavel');
        $itens_checklist['Pneus'] = sanitizar_input($_POST['eletrica_item_Pneus'] ?? 'Nao Aplicavel');
        $itens_checklist['Assento'] = sanitizar_input($_POST['eletrica_item_Assento'] ?? 'Nao Aplicavel');
        $itens_checklist['Cabine'] = sanitizar_input($_POST['eletrica_item_Cabine'] ?? 'Nao Aplicavel');
        $itens_checklist['Protetor_de_carga'] = sanitizar_input($_POST['eletrica_item_Protetor_de_carga'] ?? 'Nao Aplicavel');
        $itens_checklist['Freio_de_estacionamento'] = sanitizar_input($_POST['eletrica_item_Freio_de_estacionamento'] ?? 'Nao Aplicavel');
        $itens_checklist['Painel_de_instrumentacao'] = sanitizar_input($_POST['eletrica_item_Painel_de_instrumentacao'] ?? 'Nao Aplicavel');
        $itens_checklist['Farois'] = sanitizar_input($_POST['eletrica_item_Farois'] ?? 'Nao Aplicavel');
        $itens_checklist['Sinal_Sonoro'] = sanitizar_input($_POST['eletrica_item_Sinal_Sonoro'] ?? 'Nao Aplicavel');
        $itens_checklist['Alavanca_de_comandos'] = sanitizar_input($_POST['eletrica_item_Alavanca_de_comandos'] ?? 'Nao Aplicavel');
        $itens_checklist['Garfos'] = sanitizar_input($_POST['eletrica_item_Garfos'] ?? 'Nao Aplicavel');
        $itens_checklist['Corrente'] = sanitizar_input($_POST['eletrica_item_Corrente'] ?? 'Nao Aplicavel');
        // Retrovisores, Assento, Cabine, Protetor_de_carga, Fardil, Plataforma_do_Operador são "Nao Aplicavel" por padrão
    } elseif ($empilhadeira_tipo === 'paleteira') {
        $itens_checklist['Pedais'] = sanitizar_input($_POST['paleteira_item_Pedais'] ?? 'Nao Aplicavel');
        $itens_checklist['Bateria'] = sanitizar_input($_POST['paleteira_item_Bateria'] ?? 'Nao Aplicavel');
        $itens_checklist['Direcao'] = sanitizar_input($_POST['paleteira_item_Direcao'] ?? 'Nao Aplicavel');
        $itens_checklist['Buzina'] = sanitizar_input($_POST['paleteira_item_Buzina'] ?? 'Nao Aplicavel');
        $itens_checklist['Pneus'] = sanitizar_input($_POST['paleteira_item_Pneus'] ?? 'Nao Aplicavel'); // Pneus/Rodas para paleteira
        $itens_checklist['Freio_de_estacionamento'] = sanitizar_input($_POST['paleteira_item_Freio_de_estacionamento'] ?? 'Nao Aplicavel');
        $itens_checklist['Painel_de_instrumentacao'] = sanitizar_input($_POST['paleteira_item_Painel_de_instrumentacao'] ?? 'Nao Aplicavel');
        $itens_checklist['Farois'] = sanitizar_input($_POST['paleteira_item_Farois'] ?? 'Nao Aplicavel');
        $itens_checklist['Sinal_Sonoro'] = sanitizar_input($_POST['paleteira_item_Sinal_Sonoro'] ?? 'Nao Aplicavel');
        $itens_checklist['Alavanca_de_comandos'] = sanitizar_input($_POST['paleteira_item_Alavanca_de_comandos'] ?? 'Nao Aplicavel');
        $itens_checklist['Garfos'] = sanitizar_input($_POST['paleteira_item_Garfos'] ?? 'Nao Aplicavel');
        $itens_checklist['Corrente'] = sanitizar_input($_POST['paleteira_item_Corrente'] ?? 'Nao Aplicavel');
        $itens_checklist['Plataforma_do_Operador'] = sanitizar_input($_POST['paleteira_item_Plataforma_do_Operador'] ?? 'Nao Aplicavel');
        // Retrovisores, Assento, Cabine, Protetor_de_carga, Fardil são "Nao Aplicavel" por padrão
    }

    $nao_conformidade = sanitizar_input($_POST['nao_conformidade'] ?? 'Não');
    $nao_conformidade_info = ($nao_conformidade === 'Sim') ? sanitizar_input($_POST['nao_conformidade_info'] ?? '') : 'Nao Aplicavel';

    // Cria um array com todos os dados na ordem desejada para salvamento e relatório
    // Esta ordem é crucial para o relatório e CSV funcionarem corretamente
    $todos_os_dados = [
        $data,
        $hora,
        $nome,
        $empilhadeira_tipo,
        $turno,
        $operacao,
        $itens_checklist['Pedais'],
        $itens_checklist['Bateria'],
        $itens_checklist['Direcao'],
        $itens_checklist['Buzina'],
        $itens_checklist['Retrovisores'],
        $itens_checklist['Pneus'], // Pneus/Rodas
        $itens_checklist['Assento'],
        $itens_checklist['Cabine'],
        $itens_checklist['Protetor_de_carga'],
        $itens_checklist['Freio_de_estacionamento'],
        $itens_checklist['Painel_de_instrumentacao'],
        $itens_checklist['Farois'],
        $itens_checklist['Sinal_Sonoro'],
        $itens_checklist['Alavanca_de_comandos'],
        $itens_checklist['Garfos'],
        $itens_checklist['Corrente'],
        $itens_checklist['Fardil'],
        $itens_checklist['Plataforma_do_Operador'],
        $nao_conformidade,
        $nao_conformidade_info
    ];

    // Carrega os dados existentes do arquivo
    $dados_existentes = [];
    if (file_exists($dataFilePath) && filesize($dataFilePath) > 0) {
        $conteudo_arquivo = file_get_contents($dataFilePath);
        $dados_existentes = json_decode($conteudo_arquivo, true);
        if (!is_array($dados_existentes)) {
            $dados_existentes = []; // Garante que é um array, mesmo se o arquivo estiver corrompido
        }
    }

    // Adiciona o novo checklist ao array de dados existentes
    $dados_existentes[] = $todos_os_dados;

    // Salva de volta no arquivo em formato JSON com indentação para facilitar a leitura
    if (file_put_contents($dataFilePath, json_encode($dados_existentes, JSON_PRETTY_PRINT))) {
        echo "<script>alert('Checklist salvo com sucesso!'); window.location.href='checklist_form.html';</script>";
    } else {
        echo "<script>alert('Erro ao salvar o checklist.'); window.location.href='checklist_form.html';</script>";
    }
    exit();
}
?>