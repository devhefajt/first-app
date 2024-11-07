<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Response;

class EmployeeApiController extends Controller
{
    protected $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $employees = Employee::query();

        // If there is a search term, filter the employees
        if ($search) {
            $employees->where('first_name', 'like', "%$search%")
                ->orWhere('last_name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('position', 'like', "%$search%")
                ->orWhere('department', 'like', "%$search%")
                ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%$search%"]);
        }

        // Get paginated results
        $employees = $employees->paginate(12);

        return Response::json($employees, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('image')) {
            $validatedData['image'] = $this->fileUploadService->upload($request->file('image'), 'employee_images');
        }

        $employee = Employee::create($validatedData);

        return Response::json(['message' => 'Employee created successfully', 'employee' => $employee], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        return Response::json($employee, 200);
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

        return Response::json(['message' => 'Employee updated successfully', 'employee' => $employee], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);

        // Delete the employee image if it exists
        if ($employee->image) {
            $this->fileUploadService->delete($employee->image);
        }

        $employee->delete();

        return Response::json(['message' => 'Employee deleted successfully'], 200);
    }
}
