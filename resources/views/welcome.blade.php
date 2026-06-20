@extends('layouts.app')

@section('content')
<div style="text-align: center; padding: 4rem 0;">
    <h1 style="font-size: 3rem; margin-bottom: 1rem;">📦 Gudang Sidojoyo</h1>
    <p style="font-size: 1.25rem; color: var(--text-muted); max-width: 600px; margin: 0 auto 2.5rem;">
        Sistem manajemen pergudangan dan kasir terpadu untuk efisiensi bisnis Anda secara real-time.
    </p>
    
    @if(!session('user_id'))
        <div style="display: flex; gap: 1rem; justify-content: center;">
            <a href="/login" class="btn btn-primary" style="padding: 1rem 2rem; font-size: 1.125rem;">Masuk ke Sistem</a>
        </div>
    @else
        <div style="display: flex; gap: 1rem; justify-content: center;">
            @if(session('role') == 'owner')
                <a href="/owner" class="btn btn-primary" style="padding: 1rem 2rem; font-size: 1.125rem;">Buka Dashboard Owner</a>
            @elseif(session('role') == 'admin')
                <a href="/admin" class="btn btn-primary" style="padding: 1rem 2rem; font-size: 1.125rem;">Buka Dashboard Admin</a>
            @else
                <a href="/kasir" class="btn btn-primary" style="padding: 1rem 2rem; font-size: 1.125rem;">Buka POS Kasir</a>
            @endif
        </div>
    @endif
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
    <div class="card">
        <div style="font-size: 2rem; margin-bottom: 1rem;">📊</div>
        <h3 style="margin-top: 0;">Laporan Real-time</h3>
        <p style="color: var(--text-muted); font-size: 0.875rem;">Pantau penjualan dan performa bisnis Anda kapan saja dan di mana saja.</p>
    </div>
    <div class="card">
        <div style="font-size: 2rem; margin-bottom: 1rem;">📦</div>
        <h3 style="margin-top: 0;">Manajemen Stok</h3>
        <p style="color: var(--text-muted); font-size: 0.875rem;">Kelola inventaris dengan mudah, pantau stok masuk dan keluar secara akurat.</p>
    </div>
    <div class="card">
        <div style="font-size: 2rem; margin-bottom: 1rem;">💰</div>
        <h3 style="margin-top: 0;">POS Kasir</h3>
        <p style="color: var(--text-muted); font-size: 0.875rem;">Sistem kasir yang cepat dan responsif untuk mempercepat proses transaksi.</p>
    </div>
</div>
@endsection
