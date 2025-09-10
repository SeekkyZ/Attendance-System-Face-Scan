<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\UserLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    // แสดงรายการสถานที่ทั้งหมด (สำหรับ Admin)
    public function index()
    {
        $locations = Location::with('users')->orderBy('name')->paginate(15);
        return view('locations.index', compact('locations'));
    }
    
    // แสดงฟอร์มสร้างสถานที่ใหม่
    public function create()
    {
        return view('locations.create');
    }
    
    // บันทึกสถานที่ใหม่
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'required|integer|min:1|max:1000'
        ]);
        
        $location = Location::create($request->all());
        
        // เพิ่มผู้ใช้ที่สร้างสถานที่ให้มีสิทธิ์เข้าถึง
        $location->users()->attach(Auth::id(), [
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return redirect()->route('user-locations')
                        ->with('success', 'สร้างสถานที่เรียบร้อยแล้ว และคุณได้รับสิทธิ์เข้าถึงแล้ว');
    }
    
    // แสดงรายละเอียดสถานที่
    public function show(Location $location)
    {
        $location->load('users', 'attendances.user');
        return view('locations.show', compact('location'));
    }
    
    // แสดงฟอร์มแก้ไขสถานที่
    public function edit(Location $location)
    {
        return view('locations.edit', compact('location'));
    }
    
    // อัพเดทสถานที่
    public function update(Request $request, Location $location)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'required|integer|min:1|max:1000',
            'is_active' => 'boolean'
        ]);
        
        $location->update($request->all());
        
        return redirect()->route('locations.show', $location)
                        ->with('success', 'อัพเดทสถานที่เรียบร้อยแล้ว');
    }
    
    // ลบสถานที่
    public function destroy(Location $location)
    {
        $location->delete();
        
        return redirect()->route('locations.index')
                        ->with('success', 'ลบสถานที่เรียบร้อยแล้ว');
    }
    
    // จัดการสิทธิ์ผู้ใช้
    public function manageUsers(Location $location)
    {
        $location->load('users');
        
        // ผู้ใช้ที่ยังไม่ได้เพิ่มในสถานที่นี้
        $availableUsers = \App\Models\User::whereNotIn('id', $location->users->pluck('id'))->get();
        
        return view('locations.users', compact('location', 'availableUsers'));
    }
    
    // เพิ่มผู้ใช้เข้าสถานที่
    public function addUser(Request $request, Location $location)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);
        
        UserLocation::updateOrCreate(
            ['user_id' => $request->user_id, 'location_id' => $location->id],
            ['is_active' => true]
        );
        
        return back()->with('success', 'เพิ่มผู้ใช้เรียบร้อยแล้ว');
    }
    
    // ลบผู้ใช้จากสถานที่
    public function removeUser(Request $request, Location $location)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);
        
        UserLocation::where('user_id', $request->user_id)
                   ->where('location_id', $location->id)
                   ->delete();
        
        return back()->with('success', 'ลบผู้ใช้เรียบร้อยแล้ว');
    }
    
    // สถานที่ที่ผู้ใช้มีสิทธิ์
    public function userLocations()
    {
        $userLocations = Auth::user()->locations()
                        ->where('locations.is_active', true)
                        ->wherePivot('is_active', true)
                        ->orderBy('name')
                        ->get();
        
        return view('locations.user-locations', compact('userLocations'));
    }
}
