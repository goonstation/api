<x-open-graph-layout>
  <div class="header">
    <span>
      {{ $data->gameRound->server->name ?? 'Goonstation' }}
    </span>
    <span>
      Round #{{ number_format($data->gameRound->id) }}
    </span>
  </div>
  <div class="title">
    <strong>{{ $data->reason }}</strong>
  </div>
  <div class="content-wrap">
    <div class="content">
      <div class="details">
        <div>
          <div>
            {{ $data->target }}
            <span class="dimmed">fined</span>
            {{ $data->amount }} ⪽
            <span class="dimmed">by</span>
            {{ $data->issuer }}
          </div>
        </div>
      </div>
      <div class="footer">
        <div>
          {!! File::get(resource_path('img/icons/calendar-outline.svg')) !!}
          {{ $data->created_at->format('D, M j, Y g:i A e') }}
        </div>
      </div>
    </div>
    <div class="logo">
      <img src="@base64img(resource_path('img/logo.png'))" width="200" height="200" />
    </div>
  </div>
</x-open-graph-layout>
