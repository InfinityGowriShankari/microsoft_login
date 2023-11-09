@extends('layouts.app')

@section('content')
    <div class="container">
        Notebook Process
    </div>

    <form id="create-notebook-form" action="{{ route('microsoft.createNoteBook') }}" method="POST">
        @csrf
    </form>

    <script>
        document.getElementById('create-notebook-form').submit();
    </script>
</div>
