<?php

namespace App\Http\Controllers;

use App\Models\Image;

class PageController extends Controller
{
    public function history() {
        $images = Image::all();
        return view('page.history', compact('images'));
    }

    public function vision() {
        return view('page.vision');
    }

    public function struktur() {
        return view('page.struktur');
    }

    public function layanan() {
        $services = \App\Models\ServiceContent::all();
        return view('page.layanan', compact('services'));
    }

    public function serviceDetail($id) {
        $service = \App\Models\ServiceContent::findOrFail($id);
        return view('page.service-detail', compact('service'));
    }

    public function pengumuman() {
        $news = \App\Models\News::where('is_event', false)->where('is_published', true)->latest()->get();
        $events = \App\Models\News::where('is_event', true)->where('is_published', true)->orderBy('tanggal_acara', 'asc')->get();
        return view('page.pengumuman', compact('news', 'events'));
    }

    public function newsDetail($id) {
        $news = \App\Models\News::findOrFail($id);
        return view('page.news-detail', compact('news'));
    }

    public function pastors() {
        $pastors = \App\Models\Pastor::all();
        return view('page.pastors', compact('pastors'));
    }
}
