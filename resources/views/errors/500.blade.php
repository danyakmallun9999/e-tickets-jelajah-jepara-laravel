@extends('errors.layout')

@section('title', 'Server Error')

@section('code-display')
    <span class="error-code-digit">5</span>
    <img src="{{ asset('images/logo-kura.png') }}" alt="0" class="error-code-logo">
    <img src="{{ asset('images/logo-kura.png') }}" alt="0" class="error-code-logo">
@endsection

@section('message', 'Aduh, Ada yang Error! 🤖')
@section('description', 'Servernya lagi agak rewel nih. Tenang, tim kami udah tau dan lagi diperbaiki. Coba lagi sebentar lagi ya!')
