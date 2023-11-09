@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Microsoft Graph Data</h1>
        <div class="card">
            <div class="card-body">
                <h3>User Information</h3>
                <ul>
                    <li><strong>Name:</strong> {{ $data->displayName }}</li>
                    <li><strong>Email:</strong> {{ $data->mail }}</li>                
                </ul>
            </div>
        </div>
    </div>
@endsection
