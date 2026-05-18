<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'Halo dari API Gereja Management System',
        ]);
    }
}
