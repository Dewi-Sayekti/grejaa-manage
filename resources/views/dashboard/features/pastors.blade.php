@extends('layouts.dashboard')

@section('title', 'Profil Pemimpin Gereja')
@section('page-title', 'Profil Pemimpin Gereja')

@section('content')
<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <h3><i class="fas fa-users" style="color:var(--gold);margin-right:8px;"></i> Tim Pastoral Kami</h3>
    </div>
    <div class="card-body">
        <p style="color: var(--text-mid); font-size: 14px; margin-bottom: 24px;">
            Mengenal lebih dekat para pemimpin rohani dan pendeta yang melayani di Gereja YHS.
        </p>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px;">
            @forelse($pastors as $pastor)
                <div style="background: #fff; border: 1px solid var(--border); border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-sm); transition: all 0.3s; display: flex; flex-direction: column; align-items: center; padding: 32px 24px;">
                    
                    <div style="width: 120px; height: 120px; border-radius: 50%; overflow: hidden; margin-bottom: 16px; border: 4px solid var(--gold-light); box-shadow: var(--shadow-md);">
                        @if($pastor->image_path)
                            <img src="{{ asset($pastor->image_path) }}" alt="{{ $pastor->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="width: 100%; height: 100%; background: #eee; display: flex; align-items: center; justify-content: center; font-size: 40px; color: #ccc;">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                    </div>

                    <h3 style="font-size: 18px; font-weight: 700; color: var(--text-dark); margin-bottom: 4px; text-align: center;">{{ $pastor->name }}</h3>
                    <div style="background: var(--gold-light); color: var(--gold-dark); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; margin-bottom: 16px;">
                        {{ $pastor->role }}
                    </div>

                    <p style="color: var(--text-mid); font-size: 13px; text-align: center; line-height: 1.6; margin: 0;">
                        {{ $pastor->description ?: 'Melayani dengan sepenuh hati untuk jemaat dan kemuliaan Tuhan.' }}
                    </p>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--text-mid);">
                    <div style="font-size: 48px; color: #e5e7eb; margin-bottom: 16px;"><i class="fas fa-user-slash"></i></div>
                    <p>Belum ada data pemimpin yang ditambahkan.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
