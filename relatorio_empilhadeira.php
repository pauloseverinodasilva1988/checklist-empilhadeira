<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Checklists de Empilhadeira</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .report-entry {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            background-color: #fafafa;
        }
        .section-title {
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 10px;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 5px;
            color: #0056b3;
        }
        .item {
            margin-bottom: 5px;
            display: flex; /* Para alinhar rótulo e valor */
        }
        .item strong {
            display: inline-block;
            width: 220px; /* Ajuste conforme necessário para alinhamento */
            flex-shrink: 0; /* Evita que o strong encolha */
            padding-right: 10px;
            text-align: right;
        }
        .item span {
            flex-grow: 1; /* Permite que o span ocupe o espaço restante */
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .button-group {
            text-align: center;
            margin-bottom: 20px;
        }
        .action-button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none; /* Para links */
            display: inline-block;
            margin: 5px;
        }
        .action-button:hover {
            background-color: #0056b3;
        }
        .action-button.back {
            background-color: #6c757d;
        }
        .action-button.back:hover {
            background-color: #5a6268;
        }

        /* Estilos para impressão */
        @media print {
            .button-group {
                display: none; /* Oculta os botões na impressão */
            }
            body {
                background-color: #fff;
            }
            .container {
                box-shadow: none;
                border: none;
                max-width: 100%;
                margin: 0;
                padding: 0;
            }
            .report-entry {
                border: none;
                page-break-inside: avoid; /* Tenta evitar quebrar um registro no meio da página */
            }
        }
    </style>
</head>
<body>
    <body>
    <div class="container">
        <h1>Checklist das Empilhadeiras</h1>
        </div>
</body>
    <div class="container">
        <h2>Relatório de Checklists de Empilhadeira</h2>

        <div class="button-group">
            <button class="action-button" onclick="window.print()">Imprimir Relatório</button>
            <a href="http://192.168.0.150/checklist-empilhadeira/" class="action-button back">Voltar ao Formulário</a>
        </div>

        <?php
        $dataFilePath = 'checklist_data.txt'; // O mesmo arquivo usado para salvar

        if (file_exists($dataFilePath)) {
            $fileContent = file_get_contents($dataFilePath);
            $checklists = json_decode($fileContent, true); // Decodifica o JSON

            if (empty($checklists)) {
                echo "<p style='text-align: center;'>Nenhum checklist salvo ainda.</p>";
            } else {
                // Mapeamento de índices para labels amigáveis para exibição
                // Esta ordem deve corresponder à ordem em que os dados são salvos em processa_checklist.php
                $labels = [
                    'Data do Checklist',
                    'Hora do Checklist',
                    'Nome do Operador',
                    'Tipo de Empilhadeira',
                    'Turno',
                    'Operação',
                    'Pedais',
                    'Bateria',
                    'Direção',
                    'Buzina',
                    'Retrovisores',
                    'Pneus/Rodas', // Ajustado para corresponder ao uso em processa_checklist.php
                    'Assento',
                    'Cabine',
                    'Protetor de Carga',
                    'Freio de Estacionamento',
                    'Painel de Instrumentação',
                    'Faróis',
                    'Sinal Sonoro',
                    'Alavanca de Comandos',
                    'Garfos',
                    'Corrente',
                    'Fardil',
                    'Plataforma do Operador',
                    'Houve Não Conformidade?',
                    'Detalhes da Não Conformidade'
                ];

                $entryCount = 0;
                foreach ($checklists as $index => $checklistEntry) {
                    $entryCount++;
                    echo '<div class="report-entry">';
                    echo '<h3>Checklist #' . $entryCount . '</h3>';

                    // Seções do relatório
                    echo '<p class="section-title">DADOS GERAIS</p>';
                    echo '<p class="item"><strong>' . $labels[0] . ':</strong> <span>' . ($checklistEntry[0] ?? 'N/A') . '</span></p>';
                    echo '<p class="item"><strong>' . $labels[1] . ':</strong> <span>' . ($checklistEntry[1] ?? 'N/A') . '</span></p>';
                    echo '<p class="item"><strong>' . $labels[2] . ':</strong> <span>' . ($checklistEntry[2] ?? 'N/A') . '</span></p>';
                    echo '<p class="item"><strong>' . $labels[3] . ':</strong> <span>' . ($checklistEntry[3] ?? 'N/A') . '</span></p>';
                    echo '<p class="item"><strong>' . $labels[4] . ':</strong> <span>' . ($checklistEntry[4] ?? 'N/A') . '</span></p>';
                    echo '<p class="item"><strong>' . $labels[5] . ':</strong> <span>' . ($checklistEntry[5] ?? 'N/A') . '</span></p>';

                    echo '<p class="section-title">ITENS DE CHECKLIST</p>';
                    // Começa dos Pedais (índice 6) até Plataforma do Operador (índice 23)
                    for ($i = 6; $i <= 23; $i++) {
                        echo '<p class="item"><strong>' . $labels[$i] . ':</strong> <span>' . ($checklistEntry[$i] ?? 'N/A') . '</span></p>';
                    }

                    echo '<p class="section-title">NÃO CONFORMIDADE</p>';
                    echo '<p class="item"><strong>' . $labels[24] . ':</strong> <span>' . ($checklistEntry[24] ?? 'N/A') . '</span></p>';
                    if (($checklistEntry[24] ?? 'Nao') === 'Sim') { // Exibe detalhes apenas se "Sim"
                        echo '<p class="item"><strong>' . $labels[25] . ':</strong> <span>' . ($checklistEntry[25] ?? 'N/A') . '</span></p>';
                    }
                    
                    echo '</div>'; // close report-entry
                }
            }
        } else {
            echo "<p style='text-align: center;'>O arquivo de dados do checklist ('checklist_data.txt') não foi encontrado.</p>";
        }
        ?>
    </div>
</body>
</html>