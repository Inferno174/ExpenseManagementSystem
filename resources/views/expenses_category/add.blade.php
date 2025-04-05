@extends('layouts.userlayout')

@section('title', 'Expenses')

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
        <a href="{{ admin_url('home') }}" class="btn btn-primary" style="border: 1px solid #fff;"><i
                class="fa-solid fa-arrow-left" style="margin-right: 7px;"></i>Home</a>

        <div class="container-xl my-4">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-xl-12 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="m-0">Add Expense</h5>
                        </div>
                        <div class="card-body">
                            <form id="expenseForm" method="POST" action="{{ admin_url('expenses/store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="money_spent" class="form-label">Money Spent</label>
                                        <input type="number" class="form-control" name="money_spent" id="money_spent"
                                            required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="date" class="form-label">Date</label>
                                        <input type="date" class="form-control" name="date" id="date" required>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end mt-3">
                                        <button id="submit" type="submit" class="btn btn-primary">
                                            Submit
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>
    </body>
    <script>
        $(document).ready(function () {
            $('#expenseForm').on('click','#submit', function (e) {
                e.preventDefault();
                let isValid = true;
    
                $('.text-danger').remove();
                $('.is-invalid').removeClass('is-invalid');
    
                const category = $('#expense_category');
                const moneySpent = $('#money_spent');
                const date = $('#date');
    
                const showError = (element, message) => {
                    element.addClass('is-invalid');
                    element.after(`<small class="text-danger">${message}</small>`);
                    isValid = false;
                };
    
                if (!category.val().trim()) showError(category, 'Please select an expense category');
                if (!moneySpent.val().trim()) showError(moneySpent, 'Please enter the amount spent');
                else if (isNaN(moneySpent.val()) || parseFloat(moneySpent.val()) <= 0)
                    showError(moneySpent, 'Please enter a valid amount');
                if (!date.val().trim()) showError(date, 'Please select a date');
    
                if (isValid) {
                    console.log('Form submitted successfully'); // Debugging
                    this.submit();
                } else {
                    console.log('Validation failed'); // Debugging
                }
            });
        });
    </script>
    


    </html>

@endsection
