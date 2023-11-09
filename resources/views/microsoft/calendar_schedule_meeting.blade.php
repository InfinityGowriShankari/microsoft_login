@extends('layouts.app')

@section('content')
    <div class="container">
        Calender Process
    </div>

    <form id="create-event-form" action="{{ route('microsoft.createEvent') }}" method="POST">
        @csrf
    </form>

    <script>
        document.getElementById('create-event-form').submit();
    </script>
</div>
