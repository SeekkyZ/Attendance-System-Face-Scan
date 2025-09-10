<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // public function __construct() {}
    
    // แสดงหน้าประวัติการลงเวลา
    public function index()
    {
        return view('attendance.index');
    }

    // เพิ่มฟังก์ชัน scan สำหรับ POST /attendance/scan
    public function scan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location_id' => 'required|exists:locations,id',
            'session' => 'required|in:morning,afternoon,evening,night',
            'type' => 'required|in:check_in,check_out',
            'attendance_time' => 'required|date',
            'user_latitude' => 'required|numeric',
            'user_longitude' => 'required|numeric',
            'distance' => 'required|numeric',
            'note' => 'nullable|string',
        ]);

        Attendance::create([
            'name' => $request->name,
            'location_id' => $request->location_id,
            'session' => $request->session,
            'type' => $request->type,
            'attendance_time' => $request->attendance_time,
            'user_latitude' => $request->user_latitude,
            'user_longitude' => $request->user_longitude,
            'distance' => $request->distance,
            'note' => $request->note,
        ]);

        return redirect()->route('attendance.index')->with('success', 'บันทึกเวลาเรียบร้อย');
    }
    
    // แสดงประวัติการลงเวลา
    public function history(Request $request)
    {
        $query = Attendance::with(['location'])
                           ->where('name', $request->name)
                           ->orderBy('attendance_time', 'desc');

        if ($request->date) {
            $query->whereDate('attendance_time', $request->date);
        }
        if ($request->location_id) {
            $query->where('location_id', $request->location_id);
        }
        $attendances = $query->paginate(20);
        return view('attendance.history', compact('attendances'));
    }
    

    
    // ดูรายละเอียดการลงเวลาวันนี้
    public function today()
    {
    $name = request('name');
    $attendances = Attendance::with('location')
                ->where('name', $name)
                ->whereDate('attendance_time', Carbon::today('Asia/Bangkok'))
                ->orderBy('attendance_time', 'asc')
                ->get();
        
        return response()->json([
            'success' => true,
            'data' => $attendances->map(function ($attendance) {
                return [
                    'id' => $attendance->id,
                    'location' => $attendance->location->name,
                    'session' => $attendance->session,
                    'type' => $attendance->type,
                    'time' => $attendance->attendance_time->format('H:i:s'),
                    'distance' => number_format($attendance->distance, 2) . ' เมตร',
                    'note' => $attendance->note
                ];
            })
        ]);
    }
}
