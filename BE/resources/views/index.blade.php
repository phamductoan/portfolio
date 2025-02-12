@extends('layouts.app')

@section('title', 'Vocabulary List')

@section('content')
<div class="container jumbotron mt-5">
    <h1>Vocabulary List</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Form tìm kiếm -->
    <form action="{{ route('vocabulary.index') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search vocabulary..." value="{{ request()->input('search') }}">
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>
        <!-- Hiển thị nút phân trang -->
    <div class="d-flex justify-content-end">
        {{ $vocabularies->links('pagination::bootstrap-4') }}
    </div>
    <!-- Thêm nút export CSV -->
     <div class="row">
         <a href="{{ route('home') }}" class="btn btn-primary mb-3">Add New Vocabulary</a>
         <a href="{{ route('vocabularies.export') }}" class="btn btn-success mb-3" style="margin-left: 15px">Export CSV</a>
     </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Japanese Text</th>
                <th>Kanji</th>
                <th>Romaji</th>
                <th>Significance</th>
                <th>Unit</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vocabularies as $vocabulary)
                <tr>
                    <td>{{ $vocabulary->id }}</td>
                    <td>{{ $vocabulary->japanese_text }}</td>
                    <td>{{ $vocabulary->kanji }}</td>
                    <td>{{ $vocabulary->romaji }}</td>
                    <td>{{ $vocabulary->significance }}</td>
                    <td>{{ $vocabulary->unit }}</td>
                    <td>
                        <a href="{{ route('vocabulary.show', $vocabulary->id) }}" class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></a>
                        <a href="{{ route('vocabulary.edit', $vocabulary->id) }}" class="btn btn-warning btn-sm"><i class="fa fa-list-alt" aria-hidden="true"></i></a>
                        <form action="{{ route('vocabulary.destroy', $vocabulary->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete()">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script type="text/javascript">
    function confirmDelete() {
        return confirm('Are you sure you want to delete this record?');
    }
</script>
@endsection
