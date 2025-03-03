<x-open-graph-layout>
  <style>
    .damage {
      display: flex;
      gap: 10px;
      margin-top: 30px;
    }

    .damage>div:not(:last-child) {
      padding-right: 20px;
      margin-right: 20px;
      border-right: 1px solid rgba(255, 255, 255, .2);
    }

    .damage>div>div:first-child {
      font-weight: bold;
    }

    .damage>div>div:last-child {
      opacity: 0.8;
    }

    .bruteloss {
      color: #f34d4d
    }

    .fireloss {
      color: #ffc457
    }

    .toxloss {
      color: #24c024
    }

    .oxyloss {
      color: #9292fa
    }

    .gibbed {
      border: 1px solid #f34d4d;
      border-radius: 10px;
      padding: 7px 20px;
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
      @isset($data->last_words)
        "{{ $data->last_words }}"
      @else
        <em>No last words</em>
      @endisset
    </strong>
  </div>
  <div class="content-wrap">
    <div class="content">
      <div class="details">
        <div>
          {!! File::get(resource_path('img/icons/person-outline.svg')) !!}
          {{ $data->mob_name }}
        </div>
        @if ($data->gibbed)
          <div class="gibbed">
            Gibbed
          </div>
        @endif
      </div>
      <div class="damage">
        <div>
          <div class="bruteloss">150.715</div>
          <div>Brute</div>
        </div>
        <div>
          <div class="fireloss">159.741</div>
          <div>Fire</div>
        </div>
        <div>
          <div class="toxloss">0</div>
          <div>Toxin</div>
        </div>
        <div>
          <div class="oxyloss">259.45</div>
          <div>Oxygen</div>
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
