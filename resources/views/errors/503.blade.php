@extends('errors.layout')

@section('title', 'Sedang Pemeliharaan')

@section('code-display')
    <span class="error-code-digit">5</span>
    <img src="{{ asset('images/logo-kura.png') }}" alt="0" class="error-code-logo">
    <span class="error-code-digit">3</span>
@endsection

@section('message', 'Lagi Dandan! ✨')
@section('description', 'Website lagi dipercantik supaya makin keren dan nyaman buat kamu. Sabar ya, sebentar lagi juga selesai!')

@section('actions')
    <div class="mt-6 md:mt-8 py-3 px-4 md:px-5 bg-white/80 backdrop-blur-sm rounded-xl border border-amber-100 inline-flex items-center gap-2 md:gap-3" id="maintenance-notice">
        <i class="fa-solid fa-sync fa-spin text-base md:text-lg text-amber-500"></i>
        <span class="text-xs md:text-sm font-medium text-amber-600">Estimasi selesai: Beberapa menit lagi</span>
    </div>
@endsection
