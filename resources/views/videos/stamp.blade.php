@extends('app')

@section('content')
    <div class="container">
        <form action="{{ route('upload.videos') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="video">Upload Video File:</label>
                <input type="file" name="video" id="video" class="form-control-file">
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>
@endsection