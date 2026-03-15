 @if (isset($employees) && !$employees->isEmpty())

 @foreach ($employees as $employee)
     <tr>

         <td>{{ $employee->name }}</td>

         <td>{{ $employee->employee_code }}</td>

         <td>{{ $employee->department->name }}</td>

         <td>{{ $employee->manager->name }}</td>

         <td>{{ $employee->joining_date }}</td>

         <td>
             @include('partials.tableActions')
         </td>

     </tr>
 @endforeach
 @else
     <tr>
         <td colspan="6" class="text-center">No employees found.</td>
     </tr>
 @endif
 <tr>
     <td colspan="6" class="text-center">
         <div class="d-flex justify-content-center">
             {{ $employees->links() }}
         </div>
     </td>
 </tr>
