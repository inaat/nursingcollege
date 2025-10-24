<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('SliderController@store'), 'method' => 'post', 'id' =>'slider_add_form' ,'files' => true]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.add_new_slider')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-md-12 p-3">
                    <!-- File upload and link input in one row -->
                    <div class="row">
                        <div class="col-md-6 p-3">
                            {!! Form::label('slider_image', __('english.slider_image') . ':') !!}
                            {!! Form::file('image', ['accept' => 'image/jpg, image/jpeg, image/png','class' => 'form-control upload_slider']) !!} 
                        </div>
                        <div class="col-md-6 p-3">
                            {!! Form::label('link', __('link') . ':') !!}
                            {!! Form::text('link', null, ['class' => 'form-control', 'placeholder' => __('link')]) !!}
                        </div>
                    </div>

                    <!-- Radio buttons for type -->
                    <div class="col-md-12 p-3">
                        <label for="">{{ __('type') }}</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" id="type_app" name="type" value="1" required="required" checked>
                                    <label class="form-check-label" for="type_app">
                                        {{ __('app') }}
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" id="type_web" name="type" value="2" required="required">
                                    <label class="form-check-label" for="type_web">
                                        {{ __('web') }}
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" id="type_both" name="type" value="3" required="required">
                                    <label class="form-check-label" for="type_both">
                                        {{ __('both') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang('english.save')</button>
            <button type="button" class="btn btn-default" data-bs-dismiss="modal">@lang('english.close')</button>
        </div>
    </div>

    {!! Form::close() !!}

</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
