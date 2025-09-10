<?php

use Illuminate\Support\Facades\Route;
use App\Models\FaceEncoding;

Route::get('/debug/faces', function () {
    $faces = FaceEncoding::all(['id', 'user_id', 'label', 'image_path', 'is_active', 'created_at']);
    
    foreach ($faces as $face) {
        echo "<h3>Face ID: {$face->id}</h3>";
        echo "<p>User ID: {$face->user_id}</p>";
        echo "<p>Label: {$face->label}</p>";
        echo "<p>Image Path: {$face->image_path}</p>";
        echo "<p>Active: " . ($face->is_active ? 'Yes' : 'No') . "</p>";
        echo "<p>Created: {$face->created_at}</p>";
        
        if ($face->image_path) {
            $fullPath = storage_path('app/public/' . $face->image_path);
            echo "<p>Full Path: {$fullPath}</p>";
            echo "<p>File Exists: " . (file_exists($fullPath) ? 'Yes' : 'No') . "</p>";
            echo "<p>Asset URL: " . asset('storage/' . $face->image_path) . "</p>";
            echo "<img src='" . asset('storage/' . $face->image_path) . "' style='max-width: 200px;'><br>";
        }
        
        echo "<hr>";
    }
    
    return "Debug completed";
});
