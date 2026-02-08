@extends('errors.layout')

@section('title', 'Akses Ditolak')

@section('code-display')
    <span class="error-code-digit">4</span>
    <img src="{{ asset('images/logo-kura.png') }}" alt="0" class="error-code-logo">
    <span class="error-code-digit">3</span>
@endsection

@section('message', 'Oops, Area Terbatas! 🔒')
@section('description', 'Kayaknya kamu nggak punya akses ke halaman ini. Mungkin perlu izin khusus atau login dulu?')
