@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <h1>Vocabulary Filter</h1>
            <form id="filterForm">
                <div class="form-group">
                    <label>Select Units:</label><br>
                    <div id="units-container">
                        <!-- Checkboxes sẽ được thêm vào đây -->
                    </div>
                </div>
                <button type="button" id="fetchByUnit" class="btn btn-success">Get Vocabulary by Units</button>
                <button type="button" id="fetchAll" class="btn btn-primary">Get All Vocabulary</button>
            </form>
        </div>
        <div class="col-md-6">
            <h1>Add Vocabulary</h1>
            <form action="{{ route('vocabulary.add') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="japanese_text">Japanese Text:</label>
                    <input type="text" id="japanese_text" name="japanese_text" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="kanji">Kanji (Optional):</label>
                    <input type="text" id="kanji" name="kanji" class="form-control">
                </div>
                <div class="form-group">
                    <label for="romaji">Romaji:</label>
                    <input type="text" id="romaji" name="romaji" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="significance">Significance:</label>
                    <input type="text" id="significance" name="significance" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="unit">Unit:</label>
                    <input type="number" id="unit" name="unit" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Vocabulary</button>
            </form>
            <a href="{{ route('vocabulary.index') }}">View All Vocabulary</a>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Lấy danh sách các unit từ API
        $.ajax({
            url: '/api/units',
            method: 'GET',
            success: function(units) {
                var container = $('#units-container');
                container.empty();

                units.forEach(function(unit) {
                    var checkbox = `
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="unit${unit}" name="units[]" value="${unit}">
                            <label class="form-check-label" for="unit${unit}">Unit ${unit}</label>
                        </div>
                    `;
                    container.append(checkbox);
                });
            },
            error: function() {
                $('#units-container').html('<p>An error occurred while fetching units.</p>');
            }
        });

        $('#fetchAll').click(function() {
            window.location.href = '/reading';
        });

        $('#fetchByUnit').click(function() {
            var selectedUnits = [];
            $('input[name="units[]"]:checked').each(function() {
                selectedUnits.push($(this).val());
            });

            if (selectedUnits.length > 0) {
                window.location.href = '/reading?units=' + selectedUnits.join(',');
            } else {
                alert('Please select at least one unit.');
            }
        });
    });
</script>
@endsection
