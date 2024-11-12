<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Services\FileUploadService;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Employee::query();

        // Handle search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('position', 'like', "%$search%")
                    ->orWhere('department', 'like', "%$search%")
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%$search%"]);
            });
        }

        // Handle date range filter
        if ($startDate = $request->input('start_date')) {
            $query->where('joining_date', '>=', $startDate);
        }
        if ($endDate = $request->input('end_date')) {
            $query->where('joining_date', '<=', $endDate);
        }

        // Handle sorting
        $sortColumn = $request->input('sort_column', 'first_name'); // Default column
        $sortDirection = $request->input('sort_direction', 'asc'); // Default direction
        $query->orderBy($sortColumn, $sortDirection);

        // Get paginated results
        $employees = $query->paginate(12)->appends($request->query());

        return view('employee.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('employee.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    protected $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    public function store(StoreEmployeeRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('image')) {
            // Store the image in a different folder (e.g., 'employee_images')
            $validatedData['image'] = $this->fileUploadService->upload($request->file('image'), 'employee_images');
        }

        Employee::create($validatedData);
        return redirect()->back()->with('success', 'Employee details saved successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        return view('employee.edit', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(UpdateEmployeeRequest $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $validatedData = $request->validated();

        if ($request->hasFile('image')) {
            // Delete the previous image
            $this->fileUploadService->delete($employee->image);

            // Upload the new image
            $validatedData['image'] = $this->fileUploadService->upload($request->file('image'), 'employee_images');
        }

        $employee->update($validatedData);
        return redirect()->route('employees.index')->with('success', 'Employee updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}
