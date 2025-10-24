<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('FormFieldsController@update', [$data->id]), 'novalidate'=>"novalidate", 'method' => 'PUT', 'id' => 'faq_edit_form', 'files' => true]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.edit_faq')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            {{-- Name Field --}}
            <div class="form-group col-sm-12">
                <label>{{ __('name') }} <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ $data->name }}" placeholder="{{ __('name') }}" class="form-control" required>
            </div>

            {{-- Type Field --}}
            <div class="form-group col-sm-12">
                <label>{{ __('type') }} <span class="text-danger">*</span></label>
                <select id="edit-type-select" name="type" class="form-control edit-type-field" readonly>
                    <option value="text" {{ $data->type == 'text' ? 'selected' : '' }}>Text</option>
                    <option value="number" {{ $data->type == 'number' ? 'selected' : '' }}>Numeric</option>
                    <option value="dropdown" {{ $data->type == 'dropdown' ? 'selected' : '' }}>Dropdown</option>
                    <option value="radio" {{ $data->type == 'radio' ? 'selected' : '' }}>Radio Button</option>
                    <option value="checkbox" {{ $data->type == 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                    <option value="textarea" {{ $data->type == 'textarea' ? 'selected' : '' }}>TextArea</option>
                    <option value="file" {{ $data->type == 'file' ? 'selected' : '' }}>File Upload</option>
                </select>
            </div>

            {{-- Required Toggle --}}
            <div class="form-group col-sm-12 col-md-2">
                <label>{{ __('required') }}</label>
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" name="edit_required" id="customSwitch2" {{ $data->is_required ? 'checked' : '' }}>
                    <label class="custom-control-label" id="edit-required-toggle" for="customSwitch2"></label>
                </div>
            </div>

            {{-- Option Section --}}
           
                       @if($data->default_values)
                           <div class="edit-default-values-section " style="{{ in_array($data->type, ['dropdown', 'radio', 'checkbox']) ? '' : 'display: none' }}">
                            <div class="mt-4" data-repeater-list="default_data">
                                <div class="col-md-5 pl-0 mb-4">
                                    <button type="button" class="btn btn-success add-new-option" data-repeater-create title="Add new row">
                                        <span><i class="fa fa-plus"></i> {{__('add_new_option')}}</span>
                                    </button>
                                </div>
                                 @foreach($data->default_values as $value)
                                <div class="row edit-option-section" data-repeater-item>
                                    <div class="form-group col-md-10">
                                        <label>{{ __('option') }} - <span class="edit-option-number">{{ $loop->iteration }}</span> <span class="text-danger">*</span></label>
                                        <input type="text" name="option" value="{{ $value }}" placeholder="{{ __('text') }}" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-1 pl-0 mt-4">
                                        <button data-repeater-delete type="button" class="btn btn-icon btn-inverse-danger" title="{{ __('remove').' '.__('option') }}">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        </div>
                       @else
                    {{-- Option Section --}}
                        <div class="edit-default-values-section" style="display: none">
                            <div class="mt-4" data-repeater-list="default_data">
                                <div class="col-md-5 pl-0 mb-4">
                                    <button type="button" class="btn btn-success add-new-option" data-repeater-create title="Add new row">
                                        <span><i class="fa fa-plus"></i> {{__('add_new_option')}}</span>
                                    </button>
                                </div>
                                <div class="row edit-option-section" data-repeater-item>
                                    <div class="form-group col-md-5">
                                        <label>{{ __('option') }} - <span class="edit-option-number">1</span> <span class="text-danger">*</span></label>
                                        <input type="text" name="option" placeholder="{{__('text')}}" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-1 pl-0 mt-4">
                                        <button data-repeater-delete type="button" class="btn btn-icon btn-inverse-danger remove-default-option" title="{{__('remove').' '.__('option')}}">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
            {{-- End Option Section --}}
            @endif
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang('english.update')</button>
            <button type="button" class="btn btn-default" data-bs-dismiss="modal">@lang('english.close')</button>
        </div>

        {!! Form::close() !!}

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script>
     $(document).ready(function () {
  
        // Update the option numbers dynamically
        function updateOptionNumbers() {
            $('.edit-default-values-section .edit-option-section').each(function(index) {
                $(this).find('.edit-option-number').text(index + 1);
            });
        }


      // Initialize Repeater.js for dynamic fields
        $('.edit-default-values-section').repeater({
            initEmpty: false
            , defaultValues: {
                'option': ''
            }
            , show: function() {
                $(this).slideDown();
                updateOptionNumbers();
            }
            , hide: function(deleteElement) {
                if (confirm("Are you sure you want to remove this option?")) {
                    $(this).slideUp(deleteElement);
                    updateOptionNumbers();
                }
            }
            , ready: function(setIndexes) {
                updateOptionNumbers();
            }
        });

        // Show/hide the options section based on the selected type
        $('#edit-type-select').on('change', function () {
            const type = $(this).val();
            if (['dropdown', 'radio', 'checkbox'].includes(type)) {
                $('.edit-default-values-section').show();
            } else {
                $('.edit-default-values-section').hide();
            }
        });
  // Input validation for the "name" field
        function validateInput(event) {
            var charCode = event.which || event.keyCode;
            if (!(charCode >= 65 && charCode <= 90) && !(charCode >= 97 && charCode <= 122) && !(charCode === 32)) {
                event.preventDefault();
            }
        }
        $('input[name="name"]').on('keypress', validateInput);
        // Initial call to update indexes on page load
     updateOptionNumbers(); // Uncomment this if you want indexes updated on page load
    });
</script>
