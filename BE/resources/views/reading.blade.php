@extends('layouts.app')

@section('title', 'Reading')

@section('content')
<div class="container">
    <div class="row custom-height custom-reading">
        <div class="col-md-3"></div>
        <div class="card container mt-6 col-md-6 custom-frame">
            <div>
                <h1 class="card-header">Reading</h1>
            </div>
            <div class="vocabulary-display custome-size mt-5">
                <div id="vocab-container">
                    <div id="japanese" class="vocab-item" style="color: blue"></div>
                    <div id="kanji" class="vocab-item"></div>
                    <div id="romaji" class="vocab-item"></div>
                    <div id="significance" class="vocab-item"></div>
                </div>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>
    <div class="row custom-reading">
        <div class="col-md-3"></div>
        <div class="container mt-5 col-md-6 custom-frame">
            <button id="prevBtn" class="btn btn-secondary">Previous</button>
            <button id="nextBtn" class="btn btn-primary">Next</button>
            <button id="markNotLearnedBtn" class="btn btn-danger">Mark as Not Learned</button>
            <button id="reviewNotLearnedBtn" class="btn btn-success">Review Not Learned</button>
            <button id="shuffleBtn" class="btn btn-warning">Shuffle</button>
            <button id="orderBtn" class="btn btn-info">Order</button>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>

<script>
    let vocabularies = @json($vocabularies);
    let notLearnedVocabularies = [];
    let currentIndex = 0;
    let currentStep = 0; // 0: Japanese, 1: Kanji, 2: Romaji, 3: Significance

    function displayVocabulary(index, step) {
        const vocab = vocabularies[index];
        switch (step) {
            case 0:
                $('#japanese').text(vocab.japanese_text).show();
                $('#kanji').hide();
                $('#romaji').hide();
                $('#significance').hide();
                break;
            case 1:
                $('#kanji').text(vocab.kanji ? vocab.kanji : '').show();
                $('#romaji').hide();
                $('#significance').hide();
                break;
            case 2:
                $('#romaji').text(vocab.romaji).show();
                $('#significance').hide();
                break;
            case 3:
                $('#significance').text(vocab.significance).show();
                break;
        }
    }

    $(document).ready(function() {
        displayVocabulary(currentIndex, currentStep);

        $('#nextBtn').click(function() {
            if (currentStep < 3) {
                currentStep++;
            } else if (currentIndex < vocabularies.length - 1) {
                currentStep = 0;
                currentIndex++;
            }
            displayVocabulary(currentIndex, currentStep);
        });

        $('#prevBtn').click(function() {
            if (currentStep > 0) {
                currentStep--;
            } else if (currentIndex > 0) {
                currentStep = 3;
                currentIndex--;
            }
            displayVocabulary(currentIndex, currentStep);
        });

        $('#shuffleBtn').click(function() {
            vocabularies = vocabularies.sort(() => Math.random() - 0.5);
            currentIndex = 0;
            currentStep = 0;
            displayVocabulary(currentIndex, currentStep);
        });

        $('#orderBtn').click(function() {
            vocabularies = vocabularies.sort((a, b) => a.unit - b.unit);
            currentIndex = 0;
            currentStep = 0;
            displayVocabulary(currentIndex, currentStep);
        });

        $('#markNotLearnedBtn').click(function() {
            const currentVocab = vocabularies[currentIndex];
            if (!notLearnedVocabularies.some(vocab => vocab.id === currentVocab.id)) {
                notLearnedVocabularies.push(currentVocab);
            }
        });

        $('#reviewNotLearnedBtn').click(function() {
            if (notLearnedVocabularies.length > 0) {
                vocabularies = notLearnedVocabularies;
                currentIndex = 0;
                currentStep = 0;
                displayVocabulary(currentIndex, currentStep);
            } else {
                alert("No vocabulary marked as not learned.");
            }
        });
    });
</script>

<style>
    .custom-height {
        height: 500px; /* Chiều cao cố định */
        overflow-y: auto;
    }
    .custom-reading {
        text-align: center;
    }
    .custome-size {
        font-size: 40px;
    }
    .custom-frame {
        border: 2px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }
    .custom-reading {
        text-align: center;
        background-color: #f8f9fa;
        padding: 20px;
    }
    .btn {
        margin: 5px;
    }
</style>
@endsection
