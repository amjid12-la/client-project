@extends('frontend.layouts.frontend')

@section('meta_title', 'Terms & Conditions - Report System')

@section('frontend-content')
@php
    $pageBgColor = $termsConditions->page_bg_color ?? '#f093fb';
    $headerBgColor = $termsConditions->header_bg_color ?? '#f5576c';
    $pageTextColor = $termsConditions->page_text_color ?? '#555555';
    $pageTitle = $termsConditions->title ?? 'Terms & Conditions';
    $pageContent = $termsConditions->content ?? 'Terms & conditions content will be displayed here.';
@endphp

<style>
    .terms-page {
        background: linear-gradient(135deg, {{ $pageBgColor }} 0%, {{ $headerBgColor }} 100%);
        min-height: 100vh;
        padding: 60px 0;
    }
    .terms-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        overflow: hidden;
    }
    .terms-header {
        background: linear-gradient(135deg, {{ $pageBgColor }} 0%, {{ $headerBgColor }} 100%);
        color: white;
        padding: 40px;
        text-align: center;
    }
    .terms-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 10px;
    }
    .terms-header .last-updated {
        opacity: 0.9;
        font-size: 0.95rem;
    }
    .terms-content {
        padding: 50px;
        color: {{ $pageTextColor }};
    }
    .terms-content h2 {
        color: {{ $pageBgColor }};
        font-size: 1.5rem;
        font-weight: 600;
        margin: 30px 0 15px 0;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
    }
    .terms-content p {
        line-height: 1.8;
        margin-bottom: 15px;
    }
    .terms-content ul {
        line-height: 1.8;
        padding-left: 25px;
        margin-bottom: 15px;
    }
    .terms-content ul li {
        margin-bottom: 10px;
    }
    @media (max-width: 768px) {
        .terms-content {
            padding: 30px 20px;
        }
        .terms-header h1 {
            font-size: 2rem;
        }
    }
</style>

<div class="terms-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="terms-card">
                    <div class="terms-header">
                        <h1>{{ $pageTitle }}</h1>
                        <p class="last-updated mb-0">Last updated: {{ date('F d, Y') }}</p>
                    </div>

                    <div class="terms-content">
                        {!! $pageContent !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
