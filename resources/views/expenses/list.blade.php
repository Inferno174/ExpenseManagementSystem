@extends('layouts.userlayout')

@section('title', 'Expenses Details')

@section('content')
    <style>
        :root {
            --bg-color: #f6e6cb;
            --main-color: #153448;
            --sec-color: #3c5b6f;
            --ot-color: #948979;
        }

        /* Remove Bootstrap accordion arrow icon */

        .backbutton {
            color: #fff;
            background-color: var(--sec-color);
            padding: 10px;
            border-radius: 5px;
            transition: all 0.3s linear;
        }

        .backbutton:hover {
            background-color: #fff;
            color: var(--sec-color);
            border: 1px solid var(--sec-color);
        }


        td {
            height: 50px;
            text-align: center;
        }

        th {
            text-align: center;
        }

        .btn:hover {
            border: 1px solid var(--sec-color);
        }

        /* Remove Bootstrap accordion arrow icon */
        .accordion-button::after {
            display: none;
        }

        .apply {
            border: none;
            padding: 10px 15px;
            margin-top: 15px;
            margin-bottom: 10px;
            border-radius: 10px;
            background: var(--sec-color);
            color: #fff;
            font-weight: 500;
            margin-right: 0px;
        }

        .filter {
            background-color: var(--sec-color);
            color: #fff;
            padding: 10px;
            border-radius: 10px;
        }

        /* end */
    </style>
    <div class="container-fluid py-4">
        <a href="{{ admin_url('dashboard') }}" class="btn btn-primary" style="border: 1px solid #fff;"><i
                class="fa-solid fa-arrow-left" style="margin-right: 7px;"></i>Dashboard</a>



        <div class="container-xl my-4">
            <div class="row">
                <!-- Order Statistics -->
                <div class="col-md-12 col-lg-12 col-xl-12 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center justify-content-between pb-0">
                            <div class="card-title mb-0">
                                <h5 class="m-0 me-2">Expense Details</h5>

                            </div>

                            <div class="accordion accordion-flush" id="accordionFlushExample">
                                <div class="accordion-item">
                                    <h2
                                        class="accordion-header d-flex justify-content-between align-items-center px-3 py-2">
                                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#addExpenseModal">
                                            <i class="fa-solid fa-plus"></i> Add
                                        </button>
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#flush-collapseOne" aria-expanded="false"
                                            aria-controls="flush-collapseOne" style="padding-right: 0;">
                                            <i class="fa-solid fa-filter filter"></i>
                                        </button>
                                    </h2>
                                </div>
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-around align-items-center mb-3">
                                <form class="row g-3 "id="filter-form" method="POST" action=""
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div id="flush-collapseOne" class="accordion-collapse collapse"
                                        data-bs-parent="#accordionFlushExample" style="width: 100%;">
                                        <div class="accordion-body row d-flex justify-content-between align-items-center">
                                            <div class="col-md-6 col-xxl-3 col-12">
                                                <div>
                                                    <label for="expense_rs" class="form-label">Expense Rupees</label>
                                                    <input type="text" class="form-control" id="expense_rs"
                                                        name="expense_rs" placeholder=" "
                                                        aria-describedby="defaultFormControlHelp" />
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xxl-3 col-12">
                                                <div>
                                                    <label for="category" class="form-label">Expense Category</label>
                                                    <select class="form-select" id="category" name="category"
                                                        aria-label="Default select example">
                                                        <option value="" selected disabled>Select the status</option>
                                                        @foreach ($category as $cat)
                                                            <option value="{{ encryptId($cat->id) }}">
                                                                {{ $cat->category_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xxl-6 col-12">
                                                <div>
                                                    <label for="from" class="form-label">From
                                                        Date</label>

                                                    <input class="form-control datepicker" placeholder="Please select date"
                                                        type="date" name="from" id="from" />
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xxl-6 col-12">
                                                <div>
                                                    <label for="to" class="form-label">To
                                                        Date</label>
                                                    <input class="form-control datepicker" placeholder="Please select date"
                                                        type="date" name="to" id="to" />
                                                </div>
                                            </div>
                                        </div>
                                        <div style="display: flex; justify-content: flex-end; align-items: center;">
                                            <button class="apply" id="filter">Apply Filter</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive w-100">
                                <table class="table w-100  mb-1  table-hover" id="expensesTable">
                                    <thead>
                                        <tr>
                                            <th>
                                                <span class="fw-medium">s.no</span>
                                            </th>
                                            <th>
                                                <span class="fw-medium">Expense Category</span>
                                            </th>
                                            <th>
                                                <span class="fw-medium">Money Spent</span>
                                            </th>
                                            <th>
                                                <span class="fw-medium">Date</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal fade" id="addExpenseModal" tabindex="-1" aria-labelledby="addExpenseModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content rounded-4 shadow-sm">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="addExpenseModalLabel">
                                            <i class="fa-solid fa-file-invoice-dollar me-2"></i> Add New Expense
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>

                                    <form id="expenseForm" method="POST" action="{{ admin_url('add/expense/submit') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body px-4 py-3">
                                            <div class="row g-4">
                                                <div class="col-md-6">
                                                    <label for="expense_category" class="form-label fw-semibold">Expense
                                                        Category</label>
                                                    <select class="form-select" name="expense_category"
                                                        id="expense_category" required>
                                                        <option value="" disabled selected>Select a category</option>
                                                        @foreach ($category as $categories)
                                                            <option value="{{ encryptId($categories->id) }}">
                                                                {{ $categories->category_name }}</option>
                                                        @endforeach

                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="money_spent" class="form-label fw-semibold">Money
                                                        Spent</label>
                                                    <input type="number" class="form-control" name="money_spent"
                                                        id="money_spent" placeholder="Enter amount" required>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="date" class="form-label fw-semibold">Date</label>
                                                    <input type="date" class="form-control" name="date"
                                                        id="date" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer bg-light border-top px-4 py-3 d-flex justify-content-end">
                                            <button type="button" class="btn btn-secondary me-2"
                                                data-bs-dismiss="modal">
                                                <i class="fa-solid fa-xmark me-1"></i> Cancel
                                            </button>
                                            <button id="submitBtn" type="submit" class="btn btn-primary">
                                                <i class="fa-solid fa-check me-1"></i> Submit
                                            </button>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
                    const form = document.getElementById('expenseForm');
                    const submitBtn = document.getElementById('submitBtn');

                    submitBtn.addEventListener('click', function(e) {
                        e.preventDefault();

                        document.querySelectorAll('.text-danger').forEach(el => el.remove());
                        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

                        let isValid = true;

                        const category = document.getElementById('expense_category');
                        const moneySpent = document.getElementById('money_spent');
                        const date = document.getElementById('date');

                        const showError = (element, message) => {
                            element.classList.add('is-invalid');
                            const error = document.createElement('small');
                            error.classList.add('text-danger');
                            error.textContent = message;
                            element.parentNode.appendChild(error);
                            isValid = false;
                        };

                        if (!category || !category.value.trim()) {
                            showError(category, 'Please select an expense category');
                        }

                        if (!moneySpent || !moneySpent.value.trim()) {
                            showError(moneySpent, 'Please enter the amount spent');
                        } else if (isNaN(moneySpent.value) || parseFloat(moneySpent.value) <= 0) {
                            showError(moneySpent, 'Please enter a valid amount');
                        }

                        if (!date || !date.value.trim()) {
                            showError(date, 'Please select a date');
                        }

                        if (!isValid) return;

                        fetch("{{ route('check.remaining') }}", {
                                method: "POST",
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                                },
                                body: JSON.stringify({
                                    expense: moneySpent.value
                                })
                            })
                            .then(response => {
                                console.log('Raw Response:', response);
                                return response.json(); 
                            })
                            .then(data => {
                                console.log('Parsed JSON:', data);
                            })
                            .catch(error => {
                                console.error('AJAX Error:', error);
                            });

                    });
    </script>

    <script>
        $(document).ready(function() {

            $(".datepicker").flatpickr({
                dateFormat: "Y-m-d"
            });

            var table = $('#expensesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ admin_url('home') }}",
                    data: function(d) {
                        d.expense_rs = $('#expense_rs').val();
                        d.category = $('#category').val();
                        d.from = $('#from').val();
                        d.to = $('#to').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: true,
                    },
                    {
                        data: "category",
                        name: "expense_categories.category_name"
                    },
                    {
                        data: "amount",
                        name: "expenses.expense_rs"
                    },
                    {
                        data: "date",
                        name: "expenses.created_at"
                    },
                ],
                order: [
                    [0, 'asc']
                ],
                dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                language: {
                    search: "",
                    searchPlaceholder: "Search expenses...",
                    paginate: {
                        previous: '<i class="fas fa-angle-left"></i> Previous',
                        next: 'Next <i class="fas fa-angle-right"></i>'
                    }
                }
            });
            $("#toggleFilters").click(function() {
                $("#filterSection").slideToggle();
                $(this).text($(this).text() === "Show Filters" ? "Hide Filters" : "Show Filters");
            });

            $("#filterText1, #filterText2, #filterDropdown, #filterDate1, #filterDate2").on("keyup change",
                function() {
                    table.ajax.reload();
                });




        });
    </script>

@endsection
