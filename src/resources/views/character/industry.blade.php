@extends('web::character.layouts.view', ['viewname' => 'industry', 'breadcrumb' => trans('web::seat.industry')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.industry'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">
        {{ trans('web::seat.industry') }}
      </h3>
      @if(auth()->user()->has('character.jobs'))
        <div class="card-tools">
          <div class="input-group input-group-sm">
            <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.industry']) }}"
               class="btn btn-sm btn-light">
              <i class="fas fa-sync" data-toggle="tooltip" title="{{ trans('web::seat.update_industry') }}"></i>
            </a>
          </div>
        </div>
      @endif
    </div>
    <div class="card-body">
      <div class="mb-3">
        <select multiple="multiple" id="dt-character-selector" class="form-control" style="width: 100%;">
          @foreach($characters as $character)
            @if($character->character_id == $request->character_id)
              <option selected="selected" value="{{ $character->character_id }}">{{ $character->name }}</option>
            @else
              <option value="{{ $character->character_id }}">{{ $character->name }}</option>
            @endif
          @endforeach
        </select>
      </div>

      @include('web::common.industries.buttons.filters')

      {{ $dataTable->table() }}
    </div>
  </div>

@stop

@push('javascript')
  {!! $dataTable->scripts() !!}

  <script>
      $(document).ready(function() {
          $('#dt-character-selector')
              .select2()
              .on('change', function () {
                  window.LaravelDataTables['dataTableBuilder'].ajax.reload();
              });
      });

      function updateProgressBar() {
        $(".countdown-progressbar").each(function () {
          var expiry_time = moment.utc($(this).attr('data-expiry-time'), "YYYY-MM-DD hh:mm:ss").valueOf();
          var install_time = moment.utc($(this).attr('data-install-time'), "YYYY-MM-DD hh:mm:ss").valueOf();
          var percentage_complete = (moment.utc() - install_time) / (expiry_time - install_time);
          var percentage_rounded = (Math.round(percentage_complete * 100) / 100);
          var progress_value = percentage_rounded * 100;
          var progress_class = 'progress-bar progress-bar-striped';

          // ensure progress-bar will not exceed 100%
          if (progress_value > 100)
              progress_value = 100;

          // design progress-bar according to completion
          switch (true) {
              case (percentage_rounded < 0.4):
                  progress_class += ' progress-bar-success progress-bar-striped';
                  break;
              case (percentage_rounded >= 0.4 && percentage_rounded < 0.6):
                  progress_class += ' progress-bar-info progress-bar-striped';
                  break;
              case (percentage_rounded >= 0.6 && percentage_rounded < 0.8):
                  progress_class += ' progress-bar-warning progress-bar-striped';
                  break;
              case (percentage_rounded < 1):
                  progress_class += ' progress-bar-danger progress-bar-striped';
                  break;
              default:
                  progress_class += ' progress-bar-danger progress-bar-striped';
          }

          // draw the updated progress bar
          $(this).html(
              "<div class='progress active'>" +
              '<div class="' + progress_class + '" role="progressbar" ' +
              'aria-valuenow="' + progress_value + '" aria-valuemin="0" aria-valuemax="100" ' +
              'style="width: ' + progress_value + '%" >' + progress_value +
              '%</div>' +
              '</div>'
          );
        });
      }
  </script>
@endpush
