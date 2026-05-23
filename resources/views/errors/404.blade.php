@extends('errors.minimal')

@section('title', '404')

@section('content')
    <div class="status">
        <span class="status-code">404</span>
        Sayfa bulunamadı
    </div>

    <h1>Aradığınız sayfaya ulaşılamıyor.</h1>

    <p>
        Bağlantı hatalı olabilir veya içerik taşınmış olabilir. Ana sayfaya dönerek devam edebilir ya da bir önceki
        sayfaya geri gidebilirsiniz.
    </p>

    <div class="actions">
        <a href="{{ url('/') }}" class="btn btn-primary">Ana sayfaya dön</a>
        <a href="javascript:history.back()" class="btn btn-secondary">Geri dön</a>
    </div>
@endsection
