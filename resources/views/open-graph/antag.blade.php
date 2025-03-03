<x-open-graph-layout>
  <style>
    .win-status {
      border: 1px solid;
      border-radius: 10px;
      padding: 7px 20px;
    }

    .win-status.success {
      border-color: #24c024;
      color: #24c024;
    }

    .win-status.fail {
      border-color: #f34d4d;
      color: #f34d4d;
    }
  </style>

  <div class="header">
    <span>
      {{ $data->gameRound->server->name ?? 'Goonstation' }}
    </span>
    <span>
      Round #{{ number_format($data->gameRound->id) }}
    </span>
  </div>
  <div class="title">
    <strong>
      {{ $data->mob_name }}
      the
      {{ ucwords(str_replace('_', ' ', $data->traitor_type)) }}
    </strong>
  </div>
  <div class="content-wrap">
    <div class="content">
      <div class="details">
        @if ($data->success)
          <div class="win-status success">
            Successful
          </div>
        @else
          <div class="win-status fail">
            Failed
          </div>
        @endif
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
