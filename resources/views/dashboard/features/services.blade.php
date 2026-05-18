@extends('layouts.dashboard')

@section('title', 'Konten Pelayanan')
@section('page-title', 'Konten Pelayanan')

@section('content')
<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <h3><i class="fas fa-photo-video" style="color:var(--gold);margin-right:8px;"></i> Pelayanan & Ibadah</h3>
    </div>
    <div class="card-body">
        
        <!-- Category Tabs -->
        <div style="display: flex; gap: 12px; margin-bottom: 24px; overflow-x: auto; padding-bottom: 8px;">
            <a href="{{ route('dashboard.features.services', ['category' => 'jadwal_ibadah']) }}" 
               style="padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 14px; white-space: nowrap; transition: all 0.2s; 
               {{ $category == 'jadwal_ibadah' ? 'background: var(--gold); color: white; box-shadow: 0 4px 12px rgba(212,175,55,0.3);' : 'background: #f3f4f6; color: var(--text-dark);' }}">
               <i class="fas fa-church" style="margin-right: 6px;"></i> Jadwal Ibadah
            </a>
            
            <a href="{{ route('dashboard.features.services', ['category' => 'kelompok_kecil']) }}" 
               style="padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 14px; white-space: nowrap; transition: all 0.2s; 
               {{ $category == 'kelompok_kecil' ? 'background: var(--gold); color: white; box-shadow: 0 4px 12px rgba(212,175,55,0.3);' : 'background: #f3f4f6; color: var(--text-dark);' }}">
               <i class="fas fa-users" style="margin-right: 6px;"></i> Kelompok Kecil
            </a>
            
            <a href="{{ route('dashboard.features.services', ['category' => 'sekolah_minggu']) }}" 
               style="padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 14px; white-space: nowrap; transition: all 0.2s; 
               {{ $category == 'sekolah_minggu' ? 'background: var(--gold); color: white; box-shadow: 0 4px 12px rgba(212,175,55,0.3);' : 'background: #f3f4f6; color: var(--text-dark);' }}">
               <i class="fas fa-child" style="margin-right: 6px;"></i> Sekolah Minggu
            </a>
            
            <a href="{{ route('dashboard.features.services', ['category' => 'musik']) }}" 
               style="padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 14px; white-space: nowrap; transition: all 0.2s; 
               {{ $category == 'musik' ? 'background: var(--gold); color: white; box-shadow: 0 4px 12px rgba(212,175,55,0.3);' : 'background: #f3f4f6; color: var(--text-dark);' }}">
               <i class="fas fa-music" style="margin-right: 6px;"></i> Musik & Pujian
            </a>
        </div>

        <!-- Content Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 24px;">
            @forelse($contents as $content)
                <div style="background: #fff; border: 1px solid var(--border); border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-sm); transition: all 0.3s; display: flex; flex-direction: column;">
                    
                    <!-- Video Area -->
                    <div style="width: 100%; padding-top: 56.25%; position: relative; background: #000;">
                        @if($content->video_link)
                            @php
                                $videoId = '';
                                if(preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $content->video_link, $match)) {
                                    $videoId = $match[1];
                                }
                            @endphp
                            
                            @if($videoId)
                                <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" 
                                    src="https://www.youtube.com/embed/{{ $videoId }}" 
                                    frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                                </iframe>
                            @else
                                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display:flex; align-items:center; justify-content:center; background:#1f2937;">
                                    <a href="{{ $content->video_link }}" target="_blank" style="color: #fff; text-decoration: none; text-align: center;">
                                        <i class="fas fa-external-link-alt" style="font-size: 32px; margin-bottom: 8px;"></i><br>
                                        Buka Link Eksternal
                                    </a>
                                </div>
                            @endif
                            
                        @elseif($content->video_path)
                            <video style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;" controls>
                                <source src="{{ asset($content->video_path) }}" type="video/mp4">
                                Browser Anda tidak mendukung pemutar video.
                            </video>
                        @else
                            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display:flex; align-items:center; justify-content:center; background:#f3f4f6; color:#9ca3af; flex-direction: column;">
                                <i class="fas fa-video-slash" style="font-size: 40px; margin-bottom: 12px;"></i>
                                <span style="font-size: 13px;">Tidak ada video</span>
                            </div>
                        @endif
                    </div>

                    <!-- Details -->
                    <div style="padding: 20px;">
                        <h3 style="font-size: 16px; font-weight: 700; color: var(--text-dark); margin-bottom: 8px;">{{ $content->title }}</h3>
                        <p style="color: var(--text-mid); font-size: 13px; line-height: 1.6; margin: 0;">
                            {{ $content->description ?: 'Tidak ada deskripsi.' }}
                        </p>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: var(--text-mid); background: #fafafa; border-radius: 12px; border: 1px dashed var(--border);">
                    <div style="font-size: 48px; color: #e5e7eb; margin-bottom: 16px;"><i class="fas fa-photo-video"></i></div>
                    <p style="font-size: 16px; font-weight: 500;">Belum ada konten untuk kategori ini.</p>
                    <p style="font-size: 13px;">Silakan cek kembali nanti untuk melihat pembaruan.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
