@extends('layouts.app')

@section('title', 'Edit Vocabulary')

@section('content')
<div class="container mt-5">
    <h1>Edit Vocabulary</h1>
    <form action="{{ route('vocabulary.update', $vocabulary->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="japanese_text">Japanese Text:</label>
            <input type="text" id="japanese_text" name="japanese_text" class="form-control" value="{{ $vocabulary->japanese_text }}" required>
        </div>
        <div class="form-group">
            <label for="kanji">Kanji (Optional):</label>
            <input type="text" id="kanji" name="kanji" class="form-control" value="{{ $vocabulary->kanji }}">
        </div>
        <div class="form-group">
            <label for="romaji">Romaji:</label>
            <input type="text" id="romaji" name="romaji" class="form-control" value="{{ $vocabulary->romaji }}" required>
        </div>
        <div class="form-group">
            <label for="significance">Significance:</label>
            <input type="text" id="significance" name="significance" class="form-control" value="{{ $vocabulary->significance }}" required>
        </div>
        <div class="form-group">
            <label for="unit">Unit:</label>
            <input type="number" id="unit" name="unit" class="form-control" value="{{ $vocabulary->unit }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Vocabulary</button>
    </
