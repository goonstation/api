<x-open-graph-layout>
  <style>
    .map img {
      border: 1px solid grey;
      border-radius: 20px;
    }
  </style>

  <div class="title">
    <strong>{{ $data->name }}</strong>
  </div>
  <div class="content-wrap">
    <div class="content">
      <div class="map">
        <img src="@base64img($data->thumb_path)" width="200" height="200" />
      </div>
      @isset($data->last_built_at)
      <div class="footer">
        <div>
          {!! Storage::get('icons/calendar-outline.svg') !!}
          Last updated {{ \Carbon\Carbon::create($data->last_built_at)->ago() }}
        </div>
      </div>
      @endisset
    </div>
    <div class="logo">
      <img src="@base64img(storage_path('app/public/img/logo.png'))" width="200" height="200" />
    </div>
  </div>
</x-open-graph-layout>
