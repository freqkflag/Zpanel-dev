@extends('layouts.base')
@section('body')
    @parent
    <div class="h-screen w-screen overflow-hidden p-0 m-0">
        <iframe 
            src="{{ $ideUrl }}" 
            class="w-full h-full border-0"
            id="ide-iframe"
            allow="clipboard-read; clipboard-write"
            title="Integrated Development Environment">
        </iframe>
    </div>
@push('styles')
<style>
    body {
        overflow: hidden;
    }
    .container-fluid {
        height: 100vh;
    }
</style>
@endpush
@endsection

