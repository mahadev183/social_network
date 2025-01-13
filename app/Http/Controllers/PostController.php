<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function store(Request $request)
    {        
        $userAccount = $request->attributes->get('authenticated_user');

        // Check if user is authenticated
        if (!$userAccount) {
            return response()->json(['error' => 'User not authenticated.'], 401);
        }
        $request->validate([
            // 'user_id' => 'required|exists:user_accounts,id',
            'file' => 'required|file|mimes:jpeg,png,mp3,mp4|max:10240', // 10 MB max
        ]);

        // Try storing the file
        $filePath = $request->file('file')->store('posts', 's3');

        // Handle storage failure
        if (!$filePath) {
            return response()->json(['error' => 'File upload failed. Please try again.'], 500);
        }

        // Generate file URL
        $fileUrl = Storage::disk('s3')->url($filePath);

        // Create the post record
        $post = Post::create([
            'user_id' => $userAccount->id,
            'file' => $fileUrl,
            'created_by' => $userAccount->id,
            'modified_by' => $userAccount->id,
        ]);

        return response()->json($post, 201);
    }
}
