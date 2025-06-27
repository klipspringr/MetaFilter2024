@extends('layouts.partials.ld-json')

@section('contents')
    "@context": "https://schema.org",
    "@type": "BlogPosting",
    "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "{{ url()->current() }}"
    },
    "headline": "{{ $post->title }}",
{{--
    "author": {
        "@type": "Person",
        "name": "{{ $post->author->name }}"
    },
--}}
    "publisher": {
        "@type": "Organization",
        "name": "{{ config('app.name') }}",
{{--}
        "logo": {
            "@type": "ImageObject",
            "url": "{{ asset('images/logo.svg') }}"
        }
    },
--}}
    "datePublished": "{{ $post->created_at }}",
    "dateModified": "{{ $post->updated_at }}",
    "articleBody": "{!! strip_tags($post->body) !!}",
    "url": "{{ url()->current() }}"
@endsection
