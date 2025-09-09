<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    // แสดงหน้าประวัติการลงเวลา
    public function index()
    {
        return view('attendance.index');
    }
    
    // แสดงประวัติการลงเวลา
    public function history(Request $request)
    {
        $query = Attendance::with(['location', 'user'])
                           ->where('user_id', Auth::id())
                           ->orderBy('attendance_time', 'desc');
        
        // กรองตามวันที่ถ้ามี
        if ($request->date) {
            $query->whereDate('attendance_time', $request->date);
        }
        
        // กรองตามสถานที่ถ้ามี
        if ($request->location_id) {
            $query->where('location_id', $request->location_id);
        }
        
        $attendances = $query->paginate(20);
        $locations = Auth::user()->locations()->where('locations.is_active', true)->get();
        
        return view('attendance.history', compact('attendances', 'locations'));
    }
    

    
    // ดูรายละเอียดการลงเวลาวันนี้
    public function today()
    {
        $attendances = Attendance::with('location')
                                ->where('user_id', Auth::id())
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
