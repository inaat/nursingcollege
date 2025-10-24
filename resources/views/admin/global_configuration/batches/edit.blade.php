
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

    {!! Form::open(['url' => action('\App\Http\Controllers\BatchController@update', [$batch->id]), 'method' => 'PUT', 'id' => 'batch_edit_form' ]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.edit_batch')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
        <div class="row">
                <div class="col-md-12 p-3">
        {!! Form::label('title', __( 'english.batch_title' ) . ':*') !!}
          {!! Form::text('title', $batch->title, ['class' => 'form-control', 'required', 'placeholder' => __( 'english.batch_title' ) ]) !!}
            </div>

                <div class="col-md-12 p-3">
                    {!! Form::label('batch', __( 'english.from') . ':*') !!}
                    {!! Form::text('from', $batch->from, ['class' => 'form-control', 'required', 'placeholder' => __( 'english.from') ]) !!}
                </div>
                <div class="col-md-12 p-3">
                    {!! Form::label('batch', __( 'english.to') . ':*') !!}
                    {!! Form::text('to', $batch->to, ['class' => 'form-control', 'required', 'placeholder' => __( 'english.to') ]) !!}
                </div>

        </div>
        </div>
        <div class="modal-footer">

      <button type="submit" class="btn btn-primary">@lang( 'english.update' )</button>
      <button type="button" class="btn btn-default" data-bs-dismiss="modal">@lang( 'english.close' )</button>
    </div>
        </div>

        {!! Form::close() !!}

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
