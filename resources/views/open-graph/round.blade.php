<x-open-graph-layout>
  <div class="header">{{ $data->server->name ?? 'Goonstation' }}</div>
  <div class="title">
    <span>#{{ number_format($data->id) }}</span>
    <strong>{{ $data->latestStationName->name ?? 'Space Station 13' }}</strong>
  </div>
  <div class="content-wrap">
    <div class="content">
      <div class="details">
        <div>
          {!! Storage::get('icons/game-controller-outline.svg') !!}
          {{ $data->game_type }} Mode
        </div>
        <div>
          {!! Storage::get('icons/map-outline.svg') !!}
          {{ $data->mapRecord->name ?? $data->map }}
        </div>
      </div>
      <div class="footer">
        <div>
          {!! Storage::get('icons/calendar-outline.svg') !!}
          {{ $data->created_at->format('D, M j, Y g:i A e') }}
        </div>
      </div>
    </div>
    <div class="logo">
      <img src="@base64img(storage_path('app/public/img/logo.png'))" width="200" height="200" />
    </div>
  </div>
</x-open-graph-layout>
