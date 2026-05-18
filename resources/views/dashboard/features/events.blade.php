@extends('layouts.dashboard')

@section('title', 'Berita & Acara Gereja')
@section('page-title', 'Berita & Acara Gereja')

@section('content')

<div style="display: grid; grid-template-columns: 1fr; gap: 32px;">

    <!-- Acara Mendatang (Schedules) -->
    <div class="card">
        <div class="card-header" style="border-bottom: 2px solid var(--gold-light);">
            <h3><i class="fas fa-calendar-alt" style="color:var(--gold);margin-right:8px;"></i> Jadwal & Acara Rutin</h3>
        </div>
        <div class="card-body">
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                @forelse($schedules as $schedule)
                    <div style="display: flex; background: #fff; border: 1px solid var(--border); border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04); transition: all 0.3s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.08)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)';">
                        
                        <!-- Emoji Column -->
                        <div style="width: 80px; background: linear-gradient(135deg, var(--gold-light), #fff); display: flex; flex-direction: column; align-items: center; justify-content: center; border-right: 1px solid var(--border); padding: 16px;">
                            <span style="font-size: 36px; line-height: 1;">{{ $schedule->emoji ?? '📅' }}</span>
                            @if($schedule->day)
                                <span style="font-size: 11px; font-weight: 700; color: var(--gold-dark); margin-top: 8px; text-transform: uppercase; letter-spacing: 0.5px;">{{ $schedule->day }}</span>
                            @endif
                        </div>
                        
                        <!-- Details Column -->
                        <div style="padding: 16px; flex: 1;">
                            <h4 style="font-size: 16px; font-weight: 700; color: var(--text-dark); margin: 0 0 8px 0; line-height: 1.3;">{{ $schedule->title }}</h4>
                            
                            @if($schedule->start_time)
                                <div style="display: flex; align-items: center; color: var(--text-mid); font-size: 12px; margin-bottom: 8px; font-weight: 500;">
                                    <i class="far fa-clock" style="color: var(--gold); margin-right: 6px;"></i>
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                                    @if($schedule->end_time)
                                        - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }} WIB
                                    @endif
                                </div>
                            @endif
                            
                            <p style="color: var(--text-mid); font-size: 13px; line-height: 1.5; margin: 0;">
                                {{ $schedule->description }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--text-mid); background: #fafafa; border-radius: 12px; border: 1px dashed var(--border);">
                        <i class="far fa-calendar-times" style="font-size: 40px; color: #e5e7eb; margin-bottom: 16px;"></i>
                        <p>Belum ada jadwal acara yang tersedia.</p>
                    </div>
                @endforelse
            </div>
            
        </div>
    </div>

    <!-- Berita & Pengumuman (News) -->
    <div class="card">
        <div class="card-header" style="border-bottom: 2px solid var(--info-light);">
            <h3><i class="fas fa-newspaper" style="color:var(--info);margin-right:8px;"></i> Berita & Pengumuman Terbaru</h3>
        </div>
        <div class="card-body" style="padding: 0;">
            
            @forelse($news as $index => $item)
                <div style="padding: 24px; {{ $index !== count($news) - 1 ? 'border-bottom: 1px solid var(--border);' : '' }}">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                        <h3 style="font-size: 18px; font-weight: 700; color: var(--text-dark); margin: 0; max-width: 80%;">{{ $item->title }}</h3>
                        @if($item->published_at)
                            <span style="background: #f3f4f6; color: var(--text-mid); padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; white-space: nowrap;">
                                <i class="far fa-calendar-alt" style="margin-right: 4px;"></i>
                                {{ \Carbon\Carbon::parse($item->published_at)->format('d M Y') }}
                            </span>
                        @endif
                    </div>
                    
                    <div style="font-size: 14px; font-weight: 600; color: var(--text-dark); margin-bottom: 12px; line-height: 1.5; border-left: 3px solid var(--info); padding-left: 12px;">
                        {{ $item->excerpt }}
                    </div>
                    
                    @if($item->content)
                        <div style="color: var(--text-mid); font-size: 14px; line-height: 1.7;">
                            {!! nl2br(e($item->content)) !!}
                        </div>
                    @endif
                </div>
            @empty
                <div style="text-align: center; padding: 60px 20px; color: var(--text-mid);">
                    <i class="far fa-newspaper" style="font-size: 48px; color: #e5e7eb; margin-bottom: 16px;"></i>
                    <p style="font-size: 16px; font-weight: 500;">Belum ada berita atau pengumuman.</p>
                </div>
            @endforelse
            
        </div>
    </div>

</div>

@endsection
