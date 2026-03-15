@extends('layouts.front')
@section('styles')
@endsection
@section('content')
    <div class="mx-lg-5 mt-lg-5">

        <div class="row">

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5>Employees List</h5>
                        <button class="btn btn-primary" id="addEmployeeBtn">+ Add Employee</button>
                    </div>

                    <div class="card-body">

                        <div class="row mb-3">

                            <div class="col-md-3">
                                <input type="text" id="search" class="form-control" placeholder="Search by Name">
                            </div>

                            <div class="col-md-3">
                                <select id="department_id" class="form-control">
                                    <option value="">All Departments</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <select id="manager_id" class="form-control">
                                    <option value="">All Managers</option>
                                    @foreach ($managers as $manager)
                                        <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <input type="text" id="joining_date" class="form-control"
                                    placeholder="Joining Date Range" value="">
                            </div>

                        </div>

                        <table class="table table-bordered">

                            <thead>
                                <tr>
                                    <th>Employee Name</th>
                                    <th>Employee Code</th>
                                    <th>Department</th>
                                    <th>Manager</th>
                                    <th>Joining Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody id="employeeTable">
                                @include('partials.employee_rows')
                            </tbody>

                        </table>

                    </div>
                </div>

            </div>

            <div class="col-md-4">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card" id="employeeCard">
                    <div class="card-header d-flex justify-content-between">
                        <h5 id="formTitle">Add Employee</h5>
                        <button type="button" class="btn-close" id="closeForm">X</button>
                    </div>

                    <div class="card-body">

                        <form id="employeeForm">
                            @csrf
                            <input type="hidden" name="employee_id" id="employee_id">

                            <div class="mb-3">
                                <label>Full Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label>Employee Code</label>
                                <input type="text" name="employee_code" class="form-control"
                                    value="{{ old('employee_code') }}">
                                @error('employee_code')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label>Department</label>
                                <select name="department_id" class="form-control">
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}"
                                            {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label>Manager</label>
                                <select name="manager_id" class="form-control">
                                    <option value="">Select Manager</option>
                                    @foreach ($managers as $manager)
                                        <option value="{{ $manager->id }}"
                                            {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
                                            {{ $manager->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Joining Date</label>
                                <input type="date" name="joining_date" class="form-control">
                                @error('joining_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control">
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label>Phone</label>
                                <input type="text" name="phone" class="form-control">
                                @error('phone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label>Address</label>
                                <textarea name="address" class="form-control"></textarea>
                                @error('address')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="float-right">
                                <button class="btn btn-secondary" type="button" id="cancelForm">Cancel</button>
                                <button class="btn btn-primary" type="submit" id="saveBtn">Save Employee</button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#joining_date').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            $('#joining_date').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format(
                    'MM/DD/YYYY'));
                fetchEmployees();
            });

            $('#joining_date').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                fetchEmployees();
            });
        });

        $('#search').keyup(function() {
            fetchEmployees();
        });

        $('#department_id').change(function() {
            fetchEmployees();
        });

        $('#manager_id').change(function() {
            fetchEmployees();
        });

        function fetchEmployees() {
            let search = $('#search').val();
            let department_id = $('#department_id').val();
            let manager_id = $('#manager_id').val();
            let joining_date = $('#joining_date').val();

            $.ajax({
                url: "{{ route('employees.index') }}",
                type: "GET",
                data: {
                    search: search,
                    department_id: department_id,
                    manager_id: manager_id,
                    joining_date: joining_date
                },
                success: function(response) {
                    $("#employeeTable").html(response);
                }
            });

        }

        function saveEmployee() {

            $.ajax({

                url: "{{ route('employees.storeOrUpdate') }}",

                type: "POST",

                data: $("#employeeForm").serialize(),

                success: function(response) {

                    fetchEmployees();

                    $("#employeeForm")[0].reset();

                    $("#employee_id").val('');

                    $("#employeeForm .text-danger").remove();

                    $("#employeeForm").validate().resetForm();

                    $("#employeeCard").hide();
                },

                error: function(xhr) {

                    let errors = xhr.responseJSON.errors;

                    $(".text-danger").remove();

                    $.each(errors, function(key, value) {

                        $("[name=" + key + "]").after(
                            '<div class="text-danger">' + value[0] + '</div>'
                        );

                    });

                }

            });

        }

        $("#addEmployeeBtn").click(function() {

            $("#employeeCard").show();

            $("#formTitle").text("Add Employee");

            $("#saveBtn").text("Save Employee");

            $("#employeeForm")[0].reset();

            $("#employee_id").val('');

        });

        $(document).on("click", ".editEmployee", function() {

            let id = $(this).data("id");

            $("#employeeCard").show();

            $("#formTitle").text("Edit Employee");

            $("#saveBtn").text("Update Employee");

            $.get("/employees/" + id, function(data) {

                $("input[name=employee_id]").val(data.id);

                $("input[name=name]").val(data.name);

                $("input[name=employee_code]").val(data.employee_code);

                $("select[name=department_id]").val(data.department_id);

                $("select[name=manager_id]").val(data.manager_id);

                $("input[name=joining_date]").val(data.joining_date);

                $("input[name=email]").val(data.email);

                $("input[name=phone]").val(data.phone);

                $("textarea[name=address]").val(data.address);

            });

        });

        $(document).on("click", ".deleteEmployee", function() {

            if (!confirm("Are you sure you want to delete this employee?")) return;

            let id = $(this).data("id");

            $.ajax({

                url: "/employees/" + id,

                type: "DELETE",

                data: {
                    _token: "{{ csrf_token() }}"
                },

                success: function() {
                    fetchEmployees();
                }

            });

        });

        $("#cancelForm, #closeForm").click(function() {

            $("#employeeForm")[0].reset();

            $("#employee_id").val('');

            $("#employeeForm .text-danger").remove();

            $("#employeeForm").validate().resetForm();

            $("#employeeCard").hide();

        });
    </script>
@endsection
