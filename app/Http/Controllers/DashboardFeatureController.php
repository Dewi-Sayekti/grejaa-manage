<?php

namespace App\Http\Controllers;

use App\Models\Pastor;
use App\Models\ServiceContent;
use App\Models\News;
use App\Models\Schedule;
use Illuminate\Http\Request;

class DashboardFeatureController extends Controller
{
    public function pastors()
    {
        $pastors = Pastor::all();
        return view('dashboard.features.pastors', compact('pastors'));
    }

    public function services(Request $request)
    {
        $category = $request->query('category', 'jadwal_ibadah');
        $contents = ServiceContent::where('category', $category)->get();
        return view('dashboard.features.services', compact('contents', 'category'));
    }

    public function events()
    {
        $news = News::where('is_published', true)->orderBy('published_at', 'desc')->get();
        $schedules = Schedule::where('is_active', true)->orderBy('order')->get();
        return view('dashboard.features.events', compact('news', 'schedules'));
    }
}
