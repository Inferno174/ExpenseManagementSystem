@extends('layouts.userlayout')

@section('title', 'Expenses Category')

@section('content')
    <style>
        :root {
            --bg-color: #f6e6cb;
            --main-color: #153448;
            --sec-color: #3c5b6f;
            --ot-color: #948979;
        }

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
                <div class="col-md-12 col-lg-12 col-xl-12 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center justify-content-between pb-0">
                            <div class="card-title mb-0">
                                <h5 class="m-0 me-2">Expense Category Details</h5>
                            </div>

                            <div class="accordion accordion-flush" id="accordionFlushExample">
                                <div class="accordion-item">
                                    <h2
                                        class="accordion-header d-flex justify-content-between align-items-center px-3 py-2">
                                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#addCategoryModal">
                                            <i class="fa-solid fa-plus"></i> Add
                                        </button>
                                    </h2>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive w-100">
                                <table class="table w-100 mb-1 table-hover" id="categoryTable">
                                    <thead>
                                        <tr>
                                            <th><span class="fw-medium">S.No</span></th>
                                            <th><span class="fw-medium">Category Name</span></th>
                                            <th><span class="fw-medium">Status</span></th>
                                            <th><span class="fw-medium">Created At</span></th>
                                            <th><span class="fw-medium">Action</span></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                        <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-md modal-dialog-centered">
                                <div class="modal-content rounded-4 shadow-sm">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="addCategoryModalLabel">
                                            <i class="fa-solid fa-tags me-2"></i> Add New Category
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>

                                    <form id="categoryForm" method="POST"
                                        action="{{ admin_url('expense-category/add/submit') }}">
                                        @csrf
                                        <div class="modal-body px-4 py-3">
                                            <div class="row g-4">
                                                <div class="col-12">
                                                    <label for="category_name" class="form-label fw-semibold">Category
                                                        Name</label>
                                                    <input type="text" class="form-control" name="category_name"
                                                        id="category_name" placeholder="Enter category name" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer bg-light border-top px-4 py-3 d-flex justify-content-end">
                                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">
                                                <i class="fa-solid fa-xmark me-1"></i> Cancel
                                            </button>
                                            <button id="categorySubmitBtn" type="submit" class="btn btn-primary">
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
    <script>
        $(document).ready(function() {
            $(".datepicker").flatpickr({
                dateFormat: "Y-m-d"
            });

            var table = $('#categoryTable').DataTable({
                processing: false,
                serverSide: true,
                ajax: {
                    url: "{{ admin_url('expense-category/list') }}",
                    data: function(d) {
                        d.category = $('#category_name_search').val();
                    }
                },
                columns: [{
                    data: 'DT_RowIndex',
                            orderable: false,
                            searchable: true,
                    },
                    {
                        data: "category_name",
                        name: "category_name"
                    },
                    {
                        data: "status",
                        name: "status"
                    },
                    {
                        data: "created_at",
                        name: "created_at"
                    },
                    {
                        data: "action",
                        name: "action"
                    }
                ],
                order: [
                    [0, 'asc']
                ],
                dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                language: {
                    search: "",
                    searchPlaceholder: "Search category...",
                    paginate: {
                        previous: '<i class="fas fa-angle-left"></i> Previous',
                        next: 'Next <i class="fas fa-angle-right"></i>'
                    }
                }
            });

            $('#expense_rs, #category, #from, #to').on("change keyup", function() {
                table.ajax.reload();
            });



        });

        $('#categorySubmitBtn').on('click', function(e) {
            e.preventDefault();

            let isValid = true;
            $('.text-danger').remove();
            $('.is-invalid').removeClass('is-invalid');

            const categoryName = $('#category_name');
            const nameValue = categoryName.val().trim();

            if (nameValue === '') {
                categoryName.addClass('is-invalid');
                categoryName.after('<small class="text-danger">Category name is required</small>');
                isValid = false;
            } else if (!/^[a-zA-Z0-9\s&\-]+$/.test(nameValue)) {
                categoryName.addClass('is-invalid');
                categoryName.after(
                    '<small class="text-danger">Only letters, numbers, spaces, &, and - are allowed</small>'
                );
                isValid = false;
            }

            if (isValid) {
                $('#categoryForm')[0].submit();
            }
        });
    </script>

@endsection
