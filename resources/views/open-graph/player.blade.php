<x-open-graph-layout>
  <style>
    .stats > div:not(:last-child) {
      margin-bottom: 20px;
    }
  </style>

  <div class="title">
    <strong>
      {{ $data->key ?? $data->ckey }}
    </strong>
  </div>
  <div class="content-wrap">
    <div class="content">
      <div class="stats">
        <div>
          {{ number_format($data->participations_count - $data->participations_rp_count) }}
          <span class="dimmed">Classic Rounds</span>
        </div>
        <div>
          {{ number_format($data->participations_rp_count) }}
          <span class="dimmed">Roleplay Rounds</span>
        </div>
        <div>
          {{ number_format($data->hours_played, 2) }}
          <span class="dimmed">Hours</span>
        </div>
        <div>
          {{ number_format($data->deaths_count) }}
          <span class="dimmed">Deaths</span>
        </div>
      </div>
      <div class="footer">
        <div>
          {!! Storage::get('icons/calendar-outline.svg') !!}
          Started playing {{ $data->firstConnection->created_at->ago() }}
        </div>
      </div>
    </div>
    <div class="logo">
      <img src="@base64img(storage_path('app/public/img/logo.png'))" width="200" height="200" />
    </div>
  </div>
</x-open-graph-layout>
