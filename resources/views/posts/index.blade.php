@extends('layouts.app')

@section('title', $title ?? 'Untitled')

@section('contents')
    @if (isset($subdomain) && $subdomain ==='fanfare')
        <x-snippets.snippet-component slug="fanfare-spoilers-note" small-text=true />
    @endif

    @if (isset($subdomain) && $subdomain ==='jobs')
        <x-snippets.snippet-component slug="jobs-location-note" small-text=true />
    @endif

    @if (isset($subdomain) && $subdomain ==='metafilter')
        @auth
            <x-members.happy-birthday-component />
        @endauth
    @endif
    
    @guest
        @include('posts.partials.show-not-logged-in', [
            'context' => 'index'
        ])
    @endguest

    <livewire:posts.post-index-component />
@endsection
