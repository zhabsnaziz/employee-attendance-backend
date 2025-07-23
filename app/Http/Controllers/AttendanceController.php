<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Department;
use App\Models\AttendanceHistory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function clockIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,employee_id',
            'description' => 'nullable|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }
    
        try {
            $employee = Employee::where('employee_id', $request->employee_id)->first();
            $now      = Carbon::now('Asia/Jakarta');
            
            $existingAttendance = Attendance::where('employee_id', $employee->employee_id)
                ->whereNull('clock_out')
                ->latest('clock_in')
                ->first();
    
            if ($existingAttendance) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors'  => [
                        'employee_id' => ['Karyawan sudah melakukan clock-in dan belum clock-out.'],
                    ],
                ], 422);
            }
    
            $attendanceId = 'ATD-' . uniqid();
    
            $attendance = Attendance::create([
                'employee_id'   => $employee->employee_id,
                'attendance_id' => $attendanceId,
                'clock_in'      => $now
            ]);
    
            AttendanceHistory::create([
                'employee_id'     => $employee->employee_id,
                'attendance_id'   => $attendanceId,
                'date_attendance' => $now,
                'attendance_type' => 1,
                'description'     => $request->description,
            ]);
    
            return response()->json([
                'message' => 'Clock-in recorded',
                'data'    => $attendance
            ], 201);
    
        } catch (\Exception $e) {
            Log::error('Clock-in exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
    
            return response()->json([
                'message' => 'An unexpected error occurred during clock-in.',
            ], 500);
        }
    }
    
    public function clockOut(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,employee_id',
            'description' => 'nullable|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }
    
        try {
            $now = Carbon::now('Asia/Jakarta');
    
            $attendance = Attendance::where('employee_id', $request->employee_id)
                ->whereNull('clock_out')
                ->latest('clock_in')
                ->first();
    
            if (!$attendance) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors'  => [
                        'employee_id' => ['Karyawan belum melakukan clock-in atau sudah clock-out.']
                    ],
                ], 422);
            }
    
            $attendance->update(['clock_out' => $now]);
    
            AttendanceHistory::create([
                'employee_id'     => $attendance->employee_id,
                'attendance_id'   => $attendance->attendance_id,
                'date_attendance' => $now,
                'attendance_type' => 2,
                'description'     => $request->description,
            ]);
    
            return response()->json([
                'message' => 'Clock-out recorded',
                'data'    => $attendance
            ]);
    
        } catch (\Exception $e) {
            Log::error('Clock-out exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
    
            return response()->json([
                'message' => 'An unexpected error occurred during clock-out.',
            ], 500);
        }
    }
    
    public function logs(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date'    => 'nullable|date',
            'end_date'      => 'nullable|date',
            'department_id' => 'nullable|exists:departments,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }
    
        try {
            $query = Attendance::with(['employee.department']);
    
            if ($request->start_date && $request->end_date) {
                $query->whereBetween('clock_in', [
                    $request->start_date . ' 00:00:00',
                    $request->end_date . ' 23:59:59'
                ]);
            }
    
            if ($request->department_id) {
                $query->whereHas('employee', function ($q) use ($request) {
                    $q->where('departement_id', $request->department_id);
                });
            }
    
            $data = $query->orderBy('clock_in', 'desc')->get()->map(function ($att) {
                $employee = $att->employee;
                $dept     = $employee->department;
    
                $clockIn  = $att->clock_in->format('H:i:s');
                $clockOut = optional($att->clock_out)->format('H:i:s');
    
                return [
                    'employee_id' => $employee->employee_id,
                    'name'        => $employee->name,
                    'department'  => $dept->department_name,
                    'date'        => $att->clock_in->format('Y-m-d'),
                    'clock_in'    => $clockIn,
                    'clock_out'   => $clockOut,
                    'on_time_in'  => $clockIn <= $dept->max_clock_in_time,
                    'on_time_out' => $att->clock_out ? $clockOut >= $dept->max_clock_out_time : false
                ];
            });
    
            return response()->json($data);
    
        } catch (\Exception $e) {
            Log::error('Logs exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
    
            return response()->json([
                'message' => 'Failed to retrieve logs',
            ], 500);
        }
    }
}
