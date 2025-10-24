<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('SliderController@update', [$slider->id]), 'method' => 'PUT', 'id' => 'slider_edit_form', 'files' => true]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.edit_slider')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="row">
                <!-- Image Upload -->
                <div class="col-md-12 p-3">
                    {!! Form::label('slider_image', __('english.slider_image') . ':') !!}
                    {!! Form::file('image', ['accept' => 'image/jpg, image/jpeg, image/png', 'class' => 'form-control upload_slider']) !!}
                    @if($slider->image)
                        <div class="mt-2">
                            <img src="{{ $slider->image }}" alt="Slider Image" width="100%" height="100%" class="img-border">
                        </div>
                    @endif
                </div>

                <!-- Link Input -->
                <div class="col-md-6 p-3">
                    {!! Form::label('link', __('english.link') . ':') !!}
                    {!! Form::text('link', $slider->link, ['class' => 'form-control', 'placeholder' => __('english.enter_link')]) !!}
                </div>

                <!-- Type Radio Buttons -->
                <div class="col-md-6 p-3">
                    {!! Form::label('type', __('english.type') . ':') !!}
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-check">
                                {!! Form::radio('type', 1, $slider->type == 1, ['class' => 'form-check-input', 'id' => 'type_app']) !!}
                                {!! Form::label('type_app', __('english.app'), ['class' => 'form-check-label']) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                {!! Form::radio('type', 2, $slider->type == 2, ['class' => 'form-check-input', 'id' => 'type_web']) !!}
                                {!! Form::label('type_web', __('english.web'), ['class' => 'form-check-label']) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                {!! Form::radio('type', 3, $slider->type == 3, ['class' => 'form-check-input', 'id' => 'type_both']) !!}
                                {!! Form::label('type_both', __('english.both'), ['class' => 'form-check-label']) !!}
                            </div>
                        </div>
                    </div>
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
