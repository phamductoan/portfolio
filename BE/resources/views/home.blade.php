@extends('layouts.app')

@section('title', 'Home')

@section('content')

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">Vocabulary Management</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Filter Vocabulary</h3>
                            <form id="filterForm">
                                <div class="form-group">
                                    <label>Select Units:</label><br>
                                    <div id="units-container" class="unit-checkboxes">
                                        </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="button" id="fetchByUnit" class="btn btn-success">Get Vocabulary by Units</button>
                                    <button type="button" id="fetchAll" class="btn btn-secondary">Get All Vocabulary</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <h3>Add Vocabulary</h3>
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
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Add Vocabulary</button>
                                </div>
                            </form>
                            <div class="mt-2">
                                <a href="{{ route('vocabulary.index') }}" class="btn btn-link">View All Vocabulary</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $.ajax({
            url: '/api/units',
            method: 'GET',
            success: function(units) {
                var container = $('#units-container');
                container.empty();

                units.forEach(function(unit) {
                    var checkbox = `
                        <div class="form-check form-check-inline">
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

<style>
    .unit-checkboxes .form-check-inline {
        margin-right: 15px; /* Khoảng cách giữa các checkbox */
    }

    .card {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Đổ bóng cho card */
    }

    .card-header {
      text-align: center; /* Căn giữa tiêu đề */
    }

    .card-body {
        padding: 20px;
    }

    .btn {
        margin: 5px; /* Khoảng cách giữa các nút */
    }

    h3 {
        margin-bottom: 15px;
    }

</style>

@endsection