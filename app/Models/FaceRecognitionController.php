<?php

namespace App\Http\Controllers;

use App\Models\FaceEncoding;
use App\Models\Location;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class FaceRecognitionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    // แสดงหน้าลงทะเบียนใบหน้า
    public function registerIndex()
    {
        $userFaces = Auth::user()->faceEncodings()->where('is_active', true)->get();
        return view('face.register', compact('userFaces'));
    }
    
    // บันทึกข้อมูลใบหน้า
    public function registerFace(Request $request)
    {
        $request->validate([
            'encoding' => 'required|array',
            'encoding.*' => 'required|numeric',
            'image' => 'required|string', // base64 image
            'label' => 'nullable|string|max:255'
        ]);
        
        try {
            // บันทึกรูปภาพ
            $imageData = $request->image;
            $imageData = str_replace('data:image/png;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $imageData = base64_decode($imageData);
            
            $fileName = 'faces/' . Auth::id() . '_' . time() . '.png';
            Storage::disk('public')->put($fileName, $imageData);
            
            // บันทึก face encoding
            FaceEncoding::create([
                'user_id' => Auth::id(),
                'encoding' => $request->encoding,
                'image_path' => $fileName,
                'label' => $request->label ?: 'Face ' . (Auth::user()->faceEncodings()->count() + 1),
                'is_active' => true
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'ลงทะเบียนใบหน้าสำเร็จ'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // แสดงหน้าแสกนใบหน้า
    public function scanIndex()
    {
        return view('face.scan');
    }
    
    // ประมวลผลการแสกนใบหน้าเพื่อลงเวลา
    public function scanFace(Request $request)
    {
        $request->validate([
            'encoding' => 'required|array',
            'encoding.*' => 'required|numeric',
            'location_id' => 'required|exists:locations,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'type' => 'required|in:check_in,check_out',
            'note' => 'nullable|string|max:255'
        ]);
        
        try {
            // ค้นหาใบหน้าที่ตรงกัน
            $matchResult = FaceEncoding::findBestMatch($request->encoding);
            
            if (!$matchResult) {
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่พบใบหน้าที่ตรงกันในระบบ กรุณาลงทะเบียนใบหน้าก่อน'
                ], 404);
            }
            
            $user = $matchResult['face']->user;
            $confidence = $matchResult['confidence'];
            
            // ตรวจสอบความมั่นใจ
            if ($confidence < 0.4) { // 60% confidence threshold
                return response()->json([
                    'success' => false,
                    'message' => 'การจดจำใบหน้าไม่แน่ชัด กรุณาลองใหม่หรือลงทะเบียนใบหน้าเพิ่มเติม',
                    'confidence' => round($confidence * 100, 1)
                ], 400);
            }
            
            // ตรวจสอบสถานที่
            $location = Location::findOrFail($request->location_id);
            
            if (!$location->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'สถานที่นี้ไม่เปิดให้บริการ'
                ], 400);
            }
            
            // ตรวจสอบสิทธิ์
            if (!$user->hasAccessToLocation($location->id)) {
                return response()->json([
                    'success' => false,
                    'message' => $user->name . ' ไม่มีสิทธิ์ลงเวลาในสถานที่นี้'
                ], 403);
            }
            
            // ตรวจสอบระยะทาง
            $distance = $location->calculateDistance($request->latitude, $request->longitude);
            if (!$location->isWithinRadius($request->latitude, $request->longitude)) {
                return response()->json([
                    'success' => false,
                    'message' => $user->name . ' อยู่นอกเขตการลงเวลา (ระยะทาง: ' . number_format($distance, 2) . ' เมตร)',
                    'distance' => $distance,
                    'max_distance' => $location->radius
                ], 400);
            }
            
            // กำหนดช่วงเวลา
            $session = Attendance::getCurrentSession();
            
            // ตรวจสอบว่าได้ลงเวลาในช่วงนี้แล้วหรือยัง
            if (Attendance::hasAttendanceToday($user->id, $location->id, $session, $request->type)) {
                return response()->json([
                    'success' => false,
                    'message' => $user->name . ' ได้ลงเวลา' . ($request->type == 'check_in' ? 'เข้า' : 'ออก') . 'ในช่วงนี้แล้ววันนี้'
                ], 400);
            }
            
            // บันทึกการลงเวลา
            $attendance = Attendance::create([
                'user_id' => $user->id,
                'location_id' => $location->id,
                'session' => $session,
                'type' => $request->type,
                'attendance_time' => Carbon::now(),
                'user_latitude' => $request->latitude,
                'user_longitude' => $request->longitude,
                'distance' => $distance,
                'note' => $request->note
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'ลงเวลา' . ($request->type == 'check_in' ? 'เข้า' : 'ออก') . 'สำเร็จ',
                'data' => [
                    'user_name' => $user->name,
                    'location' => $location->name,
                    'session' => $session,
                    'type' => $request->type,
                    'time' => $attendance->attendance_time->format('H:i:s'),
                    'distance' => number_format($distance, 2) . ' เมตร',
                    'confidence' => round($confidence * 100, 1) . '%'
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // ลบข้อมูลใบหน้า
    public function deleteFace(Request $request, FaceEncoding $face)
    {
        // ตรวจสอบว่าเป็นใบหน้าของผู้ใช้เอง
        if ($face->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'คุณไม่มีสิทธิ์ลบข้อมูลนี้'
            ], 403);
        }
        
        try {
            // ลบรูปภาพ
            if ($face->image_path && Storage::disk('public')->exists($face->image_path)) {
                Storage::disk('public')->delete($face->image_path);
            }
            
            $face->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'ลบข้อมูลใบหน้าสำเร็จ'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // ดูรายการสถานที่สำหรับเลือกลงเวลา
    public function getLocations()
    {
        $locations = Auth::user()->locations()
                        ->where('is_active', true)
                        ->select('id', 'name', 'description', 'latitude', 'longitude', 'radius')
                        ->get();
        
        return response()->json([
            'success' => true,
            'data' => $locations
        ]);
    }
}
