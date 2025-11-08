@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">

    {{-- === HEADER === --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">📊 Painel Geral — Yamato Hipnose Clínica</h4>
            <span class="fw-light">Atualizado em {{ date('d/m/Y H:i') }}</span>
        </div>

        {{-- === CARDS DE RESUMO === --}}
        @php
            $ultimoMes = $dados[0] ?? null;
            $totalAtendimentos = ($ultimoMes['consulta'] ?? 0) + ($ultimoMes['tratamento'] ?? 0) + ($ultimoMes['sessaohipnose'] ?? 0) + ($ultimoMes['sessaopsicanalise'] ?? 0);
        @endphp

        <div class="card-body">
            <div class="row text-center mb-3">
                <div class="col-md-3 mb-3">
                    <div class="card border-success shadow-sm">
                        <div class="card-body">
                            <h6 class="text-success">💰 Entradas</h6>
                            <h3 class="fw-bold text-success">R$ {{ number_format($ultimoMes['entrada'] ?? 0, 2, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card border-danger shadow-sm">
                        <div class="card-body">
                            <h6 class="text-danger">📉 Saídas</h6>
                            <h3 class="fw-bold text-danger">R$ {{ number_format($ultimoMes['saida'] ?? 0, 2, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card border-secondary shadow-sm">
                        <div class="card-body">
                            <h6 class="{{ ($ultimoMes['saldo'] ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                                📊 Saldo
                            </h6>
                            <h3 class="fw-bold {{ ($ultimoMes['saldo'] ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                                R$ {{ number_format($ultimoMes['saldo'] ?? 0, 2, ',', '.') }}
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card border-info shadow-sm">
                        <div class="card-body">
                            <h6 class="text-info">🧠 Atendimentos Totais</h6>
                            <h3 class="fw-bold text-info">{{ $totalAtendimentos }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- === DISTRIBUIÇÃO DE ATENDIMENTOS === --}}
            <div class="row text-center mb-4">
                <div class="col-md-3">
                    <span class="fw-bold text-primary">Consultas:</span>
                    <h5>{{ $ultimoMes['consulta'] ?? 0 }}</h5>
                </div>
                <div class="col-md-3">
                    <span class="fw-bold text-success">Tratamentos:</span>
                    <h5>{{ $ultimoMes['tratamento'] ?? 0 }}</h5>
                </div>
                <div class="col-md-3">
                    <span class="fw-bold text-warning">Hipnose:</span>
                    <h5>{{ $ultimoMes['sessaohipnose'] ?? 0 }}</h5>
                </div>
                <div class="col-md-3">
                    <span class="fw-bold text-purple">Psicanálise:</span>
                    <h5>{{ $ultimoMes['sessaopsicanalise'] ?? 0 }}</h5>
                </div>
            </div>

            <hr>

            {{-- === GRÁFICOS === --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <h5 class="text-center mb-2">💰 Entradas x Saídas</h5>
                    <canvas id="financeChart" height="180"></canvas>
                </div>
                <div class="col-md-4">
                    <h5 class="text-center mb-2">🧠 Atendimentos por Tipo (Histórico)</h5>
                    <canvas id="sessionsChart" height="180"></canvas>
                </div>
                <div class="col-md-4">
                    <h5 class="text-center mb-2">📊 Proporção de Atendimentos ({{ $ultimoMes['data'] ?? '' }})</h5>
                    <canvas id="sessionsPieChart" height="180"></canvas>
                </div>
            </div>

            {{-- === TABELA DETALHADA === --}}
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Mês/Ano</th>
                            <th>💰 Entradas (R$)</th>
                            <th>📉 Saídas (R$)</th>
                            <th>📊 Saldo (R$)</th>
                            <th>🧠 Consultas</th>
                            <th>💼 Tratamentos</th>
                            <th>🌀 Hipnose</th>
                            <th>🪞 Psicanálise</th>
                            <th>📅 Total Atendimentos</th>
                            <th>📈 Marketing</th>
                            <th>🚗 Transporte</th>
                            <th>🏢 Sala</th>
                            <th>🍽️ Alimentação</th>
                            <th>📦 Material</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dados as $d)
                        <tr>
                            <td><strong>{{ $d['data'] }}</strong></td>
                            <td class="text-success fw-bold">{{ number_format($d['entrada'] ?? 0, 2, ',', '.') }}</td>
                            <td class="text-danger fw-bold">{{ number_format($d['saida'] ?? 0, 2, ',', '.') }}</td>
                            <td class="@if(($d['saldo'] ?? 0) >= 0) text-success @else text-danger @endif fw-bold">
                                {{ number_format($d['saldo'] ?? 0, 2, ',', '.') }}
                            </td>
                            <td>{{ $d['consulta'] ?? 0 }}</td>
                            <td>{{ $d['tratamento'] ?? 0 }}</td>
                            <td>{{ $d['sessaohipnose'] ?? 0 }}</td>
                            <td>{{ $d['sessaopsicanalise'] ?? 0 }}</td>
                            <td class="fw-bold text-primary">
                                {{
                                    ($d['consulta'] ?? 0) +
                                    ($d['tratamento'] ?? 0) +
                                    ($d['sessaohipnose'] ?? 0) +
                                    ($d['sessaopsicanalise'] ?? 0)
                                }}
                            </td>
                            <td>{{ number_format($d['marketing'] ?? 0, 2, ',', '.') }}</td>
                            <td>{{ number_format($d['transporte'] ?? 0, 2, ',', '.') }}</td>
                            <td>{{ number_format($d['sala'] ?? 0, 2, ',', '.') }}</td>
                            <td>{{ number_format($d['alimentacao'] ?? 0, 2, ',', '.') }}</td>
                            <td>{{ number_format($d['material'] ?? 0, 2, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {

    const meses = @json(array_column($dados, 'data'));
    const entradas = @json(array_column($dados, 'entrada'));
    const saidas = @json(array_column($dados, 'saida'));
    const consultas = @json(array_column($dados, 'consulta'));
    const tratamentos = @json(array_column($dados, 'tratamento'));
    const hipnoses = @json(array_column($dados, 'sessaohipnose'));
    const psicanalises = @json(array_column($dados, 'sessaopsicanalise'));

    // === Gráfico Financeiro ===
    new Chart(document.getElementById('financeChart'), {
        type: 'bar',
        data: {
            labels: meses,
            datasets: [
                { label: 'Entradas (R$)', data: entradas, backgroundColor: 'rgba(75, 192, 192, 0.7)' },
                { label: 'Saídas (R$)', data: saidas, backgroundColor: 'rgba(255, 99, 132, 0.7)' }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // === Gráfico de Linha de Atendimentos ===
    new Chart(document.getElementById('sessionsChart'), {
        type: 'line',
        data: {
            labels: meses,
            datasets: [
                { label: 'Consultas', data: consultas, borderColor: '#007bff', fill: false, tension: 0.3 },
                { label: 'Tratamentos', data: tratamentos, borderColor: '#28a745', fill: false, tension: 0.3 },
                { label: 'Hipnose', data: hipnoses, borderColor: '#ffc107', fill: false, tension: 0.3 },
                { label: 'Psicanálise', data: psicanalises, borderColor: '#6f42c1', fill: false, tension: 0.3 }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // === Gráfico de Pizza de Atendimentos do Mês Atual ===
    const ultimo = @json($ultimoMes);
    new Chart(document.getElementById('sessionsPieChart'), {
        type: 'pie',
        data: {
            labels: ['Consultas', 'Tratamentos', 'Hipnose', 'Psicanálise'],
            datasets: [{
                data: [
                    ultimo.consulta ?? 0,
                    ultimo.tratamento ?? 0,
                    ultimo.sessaohipnose ?? 0,
                    ultimo.sessaopsicanalise ?? 0
                ],
                backgroundColor: ['#007bff', '#28a745', '#ffc107', '#6f42c1']
            }]
        },
        options: {
            plugins: { legend: { position: 'bottom' } }
        }
    });
});
</script>

<style>
.text-purple { color: #6f42c1 !important; }
</style>

@endsection
