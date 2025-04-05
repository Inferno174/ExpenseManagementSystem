@extends('layouts.userlayout')

@section('title', 'Dashboard')

@section('content')
    <nav aria-label="breadcrumb p-2">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
    </nav>
    <h1 class="h2 m-2">Dashboard</h1>
    <p></p>

    <div class="container-fluid">
        <div class="row my-4">
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card">
                    <h5 class="card-header">Gross Salary</h5>
                    <div class="card-body">
                        <h5 class="card-title text-success">{{ 'Rs. ' . GetDetails()->gross_salary }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card">
                    <h5 class="card-header">Remaining Salary</h5>
                    <div class="card-body">
                        <h5 class="card-title text-success">{{ 'Rs. ' . GetDetails()->remaining_salary }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-6">
                <div class="card">
                    <h5 class="card-header">Expenses by Category</h5>
                    <div class="card-body">
                        <canvas id="expenseBarChart" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <h5 class="card-header">Expenses Line Chart</h5>
                    <div class="card-body">
                        <canvas id="lineChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch("{{ route('bar.chart') }}", {
                    method: "GET",
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data.length > 0) {
                        const labels = data.data.map(item => item.category_name);
                        const values = data.data.map(item => item.expenses);

                        const ctx = document.getElementById('expenseBarChart').getContext('2d');

                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Expenses by Category',
                                    data: values,
                                    backgroundColor: '#4caf50',
                                    borderRadius: 5,
                                    barThickness: 30,
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                return `Rs. ${context.raw}`;
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        title: {
                                            display: true,
                                            text: 'Category'
                                        },
                                        ticks: {
                                            autoSkip: false,
                                            maxRotation: 45,
                                            minRotation: 30
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'Amount (Rs)'
                                        }
                                    }
                                }
                            }
                        });
                    } else {
                        document.getElementById('expenseBarChart').parentNode.innerHTML =
                            "<p class='text-muted text-center'>No expense data available.</p>";
                    }
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                });
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch("{{ route('line.chart') }}", {
                    method: "GET",
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const chartLabels = data.data.map(item => `${item.date} (${item.category_name})`);
                        const remainingSalaryData = data.data.map(item => item.remaining_salary);

                        const ctx = document.getElementById('lineChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: chartLabels,
                                datasets: [{
                                    label: 'Remaining Salary Over Time',
                                    data: remainingSalaryData,
                                    borderColor: '#007bff',
                                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                                    tension: 0.3,
                                    fill: true,
                                    pointBackgroundColor: '#007bff',
                                    pointRadius: 4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'Remaining Salary (Rs)'
                                        }
                                    },
                                    x: {
                                        title: {
                                            display: true,
                                            text: 'Date (Category)'
                                        },
                                        ticks: {
                                            maxRotation: 45,
                                            minRotation: 45,
                                            autoSkip: false
                                        }
                                    }
                                },
                                plugins: {
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                return `Rs. ${context.parsed.y}`;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    } else {
                        console.error('Error fetching line chart data');
                    }
                })
                .catch(error => {
                    console.error('Line Chart Error:', error);
                });
        });
    </script>
@endsection
