@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            @foreach($participants as $participant)
            
            <div class="card">
                {{ $participant->name }} {{ $participant->surname }} ({{ $participant->birth }})
            </div>

            @endforeach
        </div>
    </div>
</div>
@endsection
