<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('Frontend\FrontFaqController@update', [$faq->id]), 'method' => 'PUT', 'id' => 'faq_edit_form', 'files' => true]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.edit_faq')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="row">
                        
                        <div class="col-md-12 p-3">
                            {!! Form::label('title', __('title') . ':') !!}
                            {!! Form::text('title', $faq->title, ['class' => 'form-control', 'placeholder' => __('title')]) !!}
                        </div>
                        <div class="col-md-12 p-3">
                            {!! Form::label('description', __('description') . ':') !!}
                            {!! Form::text('description', $faq->description, ['class' => 'form-control', 'placeholder' => __('description')]) !!}
                        </div>
                    </div>
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang('english.update')</button>
            <button type="button" class="btn btn-default" data-bs-dismiss="modal">@lang('english.close')</button>
        </div>
    </div>

    {!! Form::close() !!}

</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
