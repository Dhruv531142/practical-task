@extends('layouts.front')

@section('content')
    <div class="mx-lg-5 mt-lg-5">
        <div class="card" id="employeeCard">
            <div class="card-header d-flex justify-content-between">
                <h5 id="formTitle">Edit Employee</h5>
            </div>

            <div class="card-body">

                <form id="employeeForm" method="POST" action="{{ route('employees.storeOrUpdate') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="employee_id" id="employee_id" value="{{ $employee->id }}">

                    <div class="mb-3">
                        <label>Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $employee->name) }}">
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Employee Code</label>
                        <input type="text" name="employee_code" class="form-control" value="{{ old('employee_code', $employee->employee_code) }}">
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
                                    {{ old('department_id', $employee->department_id) == $department->id ? 'selected' : '' }}>
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
                                <option value="{{ $manager->id }}" {{ old('manager_id', $employee->manager_id) == $manager->id ? 'selected' : '' }}>
                                    {{ $manager->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Joining Date</label>
                        <input type="date" name="joining_date" class="form-control" value="{{ old('joining_date', $employee->joining_date) }}">
                        @error('joining_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $employee->email) }}">
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $employee->phone) }}">
                        @error('phone')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Address</label>
                        <textarea name="address" class="form-control">{{ old('address', $employee->address) }}</textarea>
                        @error('address')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Image</label>
                        <div id="imageDropzone" class="dropzone"></div>
                        @error('image')
                            <div class="text-danger">
                                {{ $errors->first('image') }}
                            </div>
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
@endsection
@section('scripts')
    <script>
        Dropzone.options.imageDropzone = {
            url: '{{ route('employees.storeOrUpdate') }}',
            maxFilesize: 2, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            maxFiles: 1,
            addRemoveLinks: true,
            headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
            size: 2,
            width: 4096,
            height: 4096
            },
            success: function (file, response) {
            $('form').find('input[name="image"]').remove()
            $('form').append('<input type="hidden" name="image" value="' + response.name + '">')
            },
            removedfile: function (file) {
            file.previewElement.remove()
            if (file.status !== 'error') {
                $('form').find('input[name="image"]').remove()
                this.options.maxFiles = this.options.maxFiles + 1
            }
            },
            init: function () {
        @if(isset($employee) && $employee->image)
            var file = {!! json_encode($employee->image) !!}
                        console.log(file.original_url);
                this.options.addedfile.call(this, file)
            this.options.thumbnail.call(this, file, file.preview ?? file.original_url)
            file.previewElement.classList.add('dz-complete')
            $('form').append('<input type="hidden" name="image" value="' + file.file_name + '">')
            this.options.maxFiles = this.options.maxFiles - 1
        @endif
            },
            error: function (file, response) {
                if ($.type(response) === 'string') {
                    var message = response //dropzone sends it's own error messages in string
                } else {
                    var message = response.errors.file
                }
                file.previewElement.classList.add('dz-error')
                _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
                _results = []
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    node = _ref[_i]
                    _results.push(node.textContent = message)
                }

                return _results
            }
        }
    </script>
@endsection
