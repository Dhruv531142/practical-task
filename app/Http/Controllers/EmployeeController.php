<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Manager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['department', 'manager']);

        if ($request->ajax()) {
            if ($request->search) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            if ($request->department_id) {
                $query->where('department_id', $request->department_id);
            }

            if ($request->manager_id) {
                $query->where('manager_id', $request->manager_id);
            }

            if ($request->joining_date) {

                $dates = explode(' - ', $request->joining_date);

                $start = Carbon::createFromFormat('m/d/Y', $dates[0])->startOfDay();
                $end = Carbon::createFromFormat('m/d/Y', $dates[1])->endOfDay();

                $query->where('joining_date', '>=', $start)->where('joining_date', '<=', $end);
            }

            $employees = $query->latest()->paginate(10);

            return view('partials.employee_rows', compact('employees'))->render();
        }

        $employees = $query->latest()->paginate(10);

        $departments = Department::all();
        $managers = Manager::all();

        return view('employees.index', compact('employees', 'departments', 'managers'));
    }

    public function create()
    {
        $departments = Department::all();
        $managers = Manager::all();

        return view('employees.create', compact('departments', 'managers'));
    }

    public function edit(Employee $employee)
    {
        $departments = Department::all();
        $managers = Manager::all();

        return view('employees.edit', compact('employee', 'departments', 'managers'));
    }

    public function storeOrUpdate(Request $request)
    {
        dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'employee_code' => 'required|string|max:255|unique:employees,employee_code,' . $request->employee_id,
            'department_id' => 'required|exists:departments,id',
            'manager_id' => 'required|exists:managers,id',
            'joining_date' => 'required|date',
            'email' => 'nullable|email|max:255|unique:employees,email,' . $request->employee_id,
            'phone' => 'nullable|numeric|digits:10',
            'address' => 'nullable|string',
            $imageRule
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $employee = Employee::updateOrCreate(
            ['id' => $request->employee_id],
            $request->only([
                'name',
                'employee_code',
                'department_id',
                'manager_id',
                'joining_date',
                'email',
                'phone',
                'address'
            ])
        );

            if ($request->hasFile('image')) {
                if ($employee->image) {
                    $employee->clearMediaCollection('images');
                }
                $employee->addMediaFromRequest('image')->toMediaCollection('images');
            }

        return response()->json([
            'success' => true
        ]);
    }

    public function show(Employee $employee)
    {
        return response()->json($employee);
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
