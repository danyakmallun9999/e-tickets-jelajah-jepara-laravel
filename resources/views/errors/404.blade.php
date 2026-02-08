@extends('errors.layout')

@section('title', 'Halaman Tidak Ditemukan')

@section('code-display')
    <span class="error-code-digit">4</span>
    <img src="{{ asset('images/logo-kura.png') }}" alt="0" class="error-code-logo">
    <span class="error-code-digit">4</span>
@endsection

@section('message', 'Waduh, Nyasar Nih! 🗺️')
@section('description', 'Halaman yang kamu cari kayaknya udah pindah atau nggak ada. Tenang, banyak tempat seru lainnya yang bisa dijelajahi!')
