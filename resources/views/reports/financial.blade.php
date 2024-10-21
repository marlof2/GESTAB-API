<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório Financeiro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .right-align {
            text-align: right;
        }
    </style>
</head>
<body>

    <h1>Relatório Financeiro</h1>

<table>
    <thead>
        <tr>
            <th>Data</th>
            <th>Serviço</th>
            <th>Profissional</th>
            <th>Situação</th>
            <th class="right-align">Valor (R$)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($query['data'] as $item)
        <tr>
            <td>{{ \Carbon\Carbon::parse($item['date'])->format('d/m/Y') }}</td>
            <td>{{ $item['service']['name'] }}</td>
            <td>{{ $item['professional']['name'] }}</td>
            <td>{{ $item['status']['name'] }}</td>
            <td class="right-align">{{ number_format($item['service']['amount'], 2, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4" class="right-align"><strong>Total:</strong></td>
            <td class="right-align"><strong>{{ $query['total_amount'] }}</strong></td>
        </tr>
    </tfoot>
</table>


</body>
</html>
