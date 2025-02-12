@extends('layouts.app')

@section('title', 'View Vocabulary')

@section('content')
<div class="container mt-5">
    <h1>Vocabulary Details</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">ID: {{ $vocabulary->id }}</h5>
            <p class="card-text"><strong>Japanese Text:</strong> {{ $vocabulary->japanese_text }}</p>
            <p class="card-text"><strong>Kanji:</strong> {{ $vocabulary->kanji }}</p>
            <p class="card-text"><strong>Romaji:</strong> {{ $vocabulary->romaji }}</p>
            <p class="card-text"><strong>Significance:</strong> {{ $vocabulary->significance }}</p>
            <p class="card-text"><strong>Unit:</strong> {{ $vocabulary->unit }}</p>
            <a href="{{ route('vocabulary.index') }}" class="btn btn-primary">Back to List</a>
        </div>
    </div>
</div>
@endsection
