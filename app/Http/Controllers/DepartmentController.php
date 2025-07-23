<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        return Department::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'department_name'    => 'required|string|max:255|unique:departments,department_name',
            'max_clock_in_time'  => 'required|date_format:H:i:s',
            'max_clock_out_time' => 'required|date_format:H:i:s',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }
    
        try {
            $validated = $validator->validated();
            
            if (strtotime($validated['max_clock_in_time']) >= strtotime($validated['max_clock_out_time'])) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors'  => [
                        'max_clock_in_time' => ['Jam masuk harus lebih awal dari jam pulang.']
                    ],
                ], 422);

            }
    
            $department = Department::create($validated);
    
            if (!$department || !$department->id) {
                Log::error('Failed to create department', ['data' => $validated]);
    
                return response()->json([
                    'message' => 'Failed to create department',
                ], 500);
            }
    
            return response()->json([
                'message' => 'Department created successfully',
                'data'    => $department
            ], 201);
    
        } catch (\Exception $e) {
            Log::error('Exception while creating department', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
    
            return response()->json([
                'message' => 'An unexpected error occurred while creating the department',
            ], 500);
        }
    }

    public function show(string $id)
    {
        $department = Department::findOrFail($id);
        return response()->json($department);
    }

    public function update(Request $request, string $id)
    {
        $department = Department::findOrFail($id);
    
        $validator = Validator::make($request->all(), [
            'department_name'    => 'required|string|max:255|unique:departments,department_name,' . $department->id,
            'max_clock_in_time'  => 'required|date_format:H:i:s',
            'max_clock_out_time' => 'required|date_format:H:i:s',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }
    
        try {
            $validated = $validator->validated();
    
            if (strtotime($validated['max_clock_in_time']) >= strtotime($validated['max_clock_out_time'])) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors'  => [
                        'max_clock_in_time' => ['Jam masuk harus lebih awal dari jam pulang.']
                    ],
                ], 422);
            }
    
            $updated = $department->update($validated);
    
            if (!$updated) {
                Log::error('Failed to update department', [
                    'department_id' => $department->id,
                    'data'          => $validated
                ]);
    
                return response()->json([
                    'message' => 'Failed to update department',
                ], 500);
            }
    
            return response()->json([
                'message' => 'Department updated successfully',
                'data'    => $department->fresh()
            ], 200);
    
        } catch (\Exception $e) {
            Log::error('Exception while updating department', [
                'department_id' => $department->id,
                'error'         => $e->getMessage(),
                'trace'         => $e->getTraceAsString()
            ]);
    
            return response()->json([
                'message' => 'An unexpected error occurred while updating the department',
            ], 500);
        }
    }
    
    public function destroy(string $id)
    {
        $department = Department::findOrFail($id);
    
        try {
            $deleted = $department->delete();
    
            if (!$deleted) {
                Log::error('Failed to delete department', ['department_id' => $id]);
    
                return response()->json([
                    'message' => 'Failed to delete department',
                ], 500);
            }
    
            return response()->json([
                'message' => 'Department deleted successfully'
            ], 200);
    
        } catch (\Exception $e) {
            Log::error('Exception while deleting department', [
                'department_id' => $id,
                'error'         => $e->getMessage(),
                'trace'         => $e->getTraceAsString()
            ]);
    
            return response()->json([
                'message' => 'An unexpected error occurred while deleting the department',
            ], 500);
        }
    }
}

