@extends('layouts.app')

@section('content')
    <form action="{{ route('upload-pdf') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file">
        <button type="submit">Upload PDF</button>
    </form>
@endsection