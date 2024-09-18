@extends('layouts.app')

@section('content')
    <div class="list-group">
        @foreach($shows['response'] as $show)
          <a href="{{ route('shows.show', $show['id']) }}" class="list-group-item list-group-item-action">{{ $show['name'] }}</a>
        @endforeach
    </div>
@endsection