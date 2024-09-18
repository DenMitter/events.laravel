@extends('layouts.app')

@section('content')
    <div class="list-group">
      @foreach($events['response'] as $event)
        <a href="{{ route('events.show', $event['id']) }}" class="list-group-item list-group-item-action">
          Меропртиятие <span style="color: #6f42c1;">№{{ $event['showId'] }}</span> состоится <span style="color: #6f42c1;">{{ $event['date']->translatedFormat('Y года, d F в H:i') }}</span>
        </a>
      @endforeach
    </div>
@endsection