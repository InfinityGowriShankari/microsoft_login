@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Error</h1>
        <div class="alert alert-danger">
            {{ $error }}
        </div>
    </div>
@endsection
