<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('department')->get();

        return response()->json($employees);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id'    => 'required|unique:employees',
            'name'           => 'required',
            'address'        => 'nullable',
            'departement_id' => 'required|exists:departments,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }
    
        try {
            $validated = $validator->validated();
    
            $employee = Employee::create($validated);
    
            if (!$employee || !$employee->id) {
                Log::error('Failed to create employee', ['data' => $validated]);
    
                return response()->json([
                    'message' => 'Failed to create employee',
                ], 500);
            }
    
            return response()->json([
                'message' => 'Employee created successfully',
                'data'    => $employee
            ], 201);
    
        } catch (\Exception $e) {
            Log::error('Exception while creating employee', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
    
            return response()->json([
                'message' => 'An unexpected error occurred while creating the employee',
            ], 500);
        }
    }

    public function show(string $id)
    {
        $employee = Employee::with('department')->findOrFail($id);

        return response()->json($employee);
    }
    
    public function update(Request $request, string $id)
    {
        $employee = Employee::findOrFail($id);
    
        $validator = Validator::make($request->all(), [
            'employee_id'    => 'required|unique:employees,employee_id,' . $employee->id,
            'name'           => 'required',
            'address'        => 'nullable',
            'departement_id' => 'required|exists:departments,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }
    
        try {
            $validated = $validator->validated();
    
            $updated = $employee->update($validated);
    
            if (!$updated) {
                Log::error('Failed to update employee', [
                    'employee_id' => $employee->id,
                    'data'        => $validated
                ]);
    
                return response()->json([
                    'message' => 'Failed to update employee',
                ], 500);
            }
    
            return response()->json([
                'message' => 'Employee updated successfully',
                'data'    => $employee->fresh()
            ], 200);
    
        } catch (\Exception $e) {
            Log::error('Exception while updating employee', [
                'employee_id' => $employee->id,
                'error'       => $e->getMessage(),
                'trace'       => $e->getTraceAsString()
            ]);
    
            return response()->json([
                'message' => 'An unexpected error occurred while updating the employee',
            ], 500);
        }
    }
    
    public function destroy(string $id)
    {
        $employee = Employee::find($id);
    
        if (!$employee) {
            return response()->json([
                'message' => 'Employee not found'
            ], 404);
        }
    
        try {
            $deleted = $employee->delete();
    
            if (!$deleted) {
                Log::error('Failed to delete employee', [
                    'employee_id' => $employee->id,
                ]);
    
                return response()->json([
                    'message' => 'Failed to delete employee',
                ], 500);
            }
    
            return response()->json([
                'message' => 'Employee deleted successfully',
            ], 200);
    
        } catch (\Exception $e) {
            Log::error('Exception while deleting employee', [
                'employee_id' => $employee->id,
                'error'       => $e->getMessage(),
                'trace'       => $e->getTraceAsString()
            ]);
    
            return response()->json([
                'message' => 'An unexpected error occurred while deleting the employee',
            ], 500);
        }
    }
}
