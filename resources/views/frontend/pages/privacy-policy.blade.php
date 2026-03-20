@extends('frontend.layouts.frontend')

@section('meta_title', 'Privacy Policy - Report System')

@section('frontend-content')
@php
    $pageBgColor = $privacyPolicy->page_bg_color ?? '#667eea';
    $headerBgColor = $privacyPolicy->header_bg_color ?? '#764ba2';
    $pageTextColor = $privacyPolicy->page_text_color ?? '#555555';
    $pageTitle = $privacyPolicy->title ?? 'Privacy Policy';
    $pageContent = $privacyPolicy->content ?? 'Privacy policy content will be displayed here.';
@endphp

<style>
    .policy-page {
        background: linear-gradient(135deg, {{ $pageBgColor }} 0%, {{ $headerBgColor }} 100%);
        min-height: 100vh;
        padding: 60px 0;
    }
    .policy-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        overflow: hidden;
    }
    .policy-header {
        background: linear-gradient(135deg, {{ $pageBgColor }} 0%, {{ $headerBgColor }} 100%);
        color: white;
        padding: 40px;
        text-align: center;
    }
    .policy-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 10px;
    }
    .policy-header .last-updated {
        opacity: 0.9;
        font-size: 0.95rem;
    }
    .policy-content {
        padding: 50px;
        color: {{ $pageTextColor }};
    }
    .policy-content h2 {
        color: {{ $pageBgColor }};
        font-size: 1.5rem;
        font-weight: 600;
        margin: 30px 0 15px 0;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
    }
    .policy-content p {
        line-height: 1.8;
        margin-bottom: 15px;
    }
    .policy-content ul {
        line-height: 1.8;
        padding-left: 25px;
        margin-bottom: 15px;
    }
    .policy-content ul li {
        margin-bottom: 10px;
    }
    @media (max-width: 768px) {
        .policy-content {
            padding: 30px 20px;
        }
        .policy-header h1 {
            font-size: 2rem;
        }
    }
</style>

<div class="policy-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="policy-card">
                    <div class="policy-header">
                        <h1>{{ $pageTitle }}</h1>
                        <p class="last-updated mb-0">Last updated: {{ date('F d, Y') }}</p>
                    </div>

                    <div class="policy-content">
                        {!! $pageContent !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
