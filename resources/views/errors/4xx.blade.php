@extends('errors.minimal')

@section('title', (string) $exception->getStatusCode())

@section('content')
    <div class="status">
        <span class="status-code">{{ $exception->getStatusCode() }}</span>
        İstek işlenemedi
    </div>

    <h1>{{ __('Bir hata oluştu.') }}</h1>

    <p>
        İsteğiniz şu anda tamamlanamıyor. Lütfen ana sayfaya dönüp tekrar deneyin.
    </p>

    <div class="actions">
        <a href="{{ url('/') }}" class="btn btn-primary">Ana sayfaya dön</a>
    </div>
@endsection
