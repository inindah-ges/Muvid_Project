@extends('frontend.template.main')

@section('title', 'Beranda')

@section('content')
    <!-- Hero Start -->
    @include('frontend._hero')
    <!-- Hero End -->

    <!-- About Start -->
    @include('frontend._about')
    <!-- About End -->

    <!-- Service Start -->
    @include('frontend._service')
    <!-- Service End -->

    <!-- Events Start -->
    @include('frontend._event')
    <!-- Events End -->

    <!-- Menu Start -->
    @include('frontend._menu')
    <!-- Menu End -->

    <!-- Team Start -->
    @include('frontend._chef-team')
    <!-- Team End -->

    <!-- Testimonials Start -->
    @include('frontend._testimonial')
    <!-- Testimonials End -->

    <!-- Contact Start -->
    @include('frontend._contact')
    <!-- Contact End -->

    <!-- Map Start -->
    @include('frontend._map')
    <!-- Map End -->
@endsection
