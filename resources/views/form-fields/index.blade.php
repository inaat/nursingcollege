@extends("admin_layouts.app")
@section('title', __('Manage Form Fields'))
@section('title', __('Manage Form Fields'))
@section('style')
<style>
    .page-section1111 {
        border: 1px solid lightgray;
        padding: 1rem;
    }

    .file-upload-default {
        visibility: hidden;
        position: absolute;
    }

    .file-upload-info {
        background: white;
    }

</style>
<link rel="stylesheet" href="{{ asset('/assets/jquery-toast-plugin/jquery.toast.min.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css">

@endsection
@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.manage_your_gallery')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('Manage Form Fields')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        Create Form Fields
                    </h4>
                    <form class="create-form pt-3" id="create-form" action="{{ url('form-fields') }}" method="POST" novalidate="novalidate" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="form-group col-sm-12 col-md-5">
                                <label>{{ __('name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name" onkeypress="validateInput(event)" placeholder="{{__('name')}}" class="form-control" required>
                            </div>
                            <div class="form-group col-sm-12 col-md-5">
                                <label>{{ __('type') }} <span class="text-danger">*</span></label>
                                <select name="type" id="type-field" class="form-control type-field">
                                    <option value="text" selected>{{__('Text')}}</option>
                                    <option value="number">{{ __('Numeric') }}</option>
                                    <option value="dropdown">{{ __('Dropdown') }}</option>
                                    <option value="radio">{{ __('Radio Button') }}</option>
                                    <option value="checkbox">{{ __('Checkbox') }}</option>
                                    <option value="textarea">{{ __('TextArea') }}</option>
                                    <option value="file">{{ __('File Upload') }}</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-2">
                                <label>{{ __('required') }} </label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input required-field" name="required" id="customSwitch1">
                                    <label class="custom-control-label" for="customSwitch1"></label>
                                </div>
                            </div>
                        </div>

                        {{-- Option Section --}}
                        <div class="default-values-section" style="display: none">
                            <div class="mt-4" data-repeater-list="default_data">
                                <div class="col-md-5 pl-0 mb-4">
                                    <button type="button" class="btn btn-success add-new-option" data-repeater-create title="Add new row">
                                        <span><i class="fa fa-plus"></i> {{__('add_new_option')}}</span>
                                    </button>
                                </div>
                                <div class="row option-section" data-repeater-item>
                                    <div class="form-group col-md-5">
                                        <label>{{ __('option') }} - <span class="option-number">1</span> <span class="text-danger">*</span></label>
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




                </div>
                <div class="d-lg-flex align-items-center mb-4 gap-3">
                    <div class="ms-auto">
                        <input class="btn btn-primary radius-30 mt-2 mt-lg-0 float-right ml-3" id="create-btn" type="submit" value={{ __('submit') }}>


                    </div>
                </div>

                </form>

            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-primary">Admission Form Custom Fields</h5>


                    <hr>
                    @can('gallery.view')
                    <div class="table-responsive">
                        <table class="table mb-0" width="100%" id="form_fields_table">
                            <thead class="table-light" width="100%">
                                <tr>
                                    <th >@lang( 'english.action' )</th>
                                    <th>@lang( 'School Type' )</th>
                                    <th>@lang( 'english.name' )</th>
                                    <th>@lang( 'english.type' )</th>
                                    <th>@lang( 'is Required' )</th>
                                    <th>@lang( 'Default Value' )</th>
                                    <th>@lang( 'Rank' )</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                    @endcan
                </div>
            </div>
        </div>

        <!--end row-->

    </div>
</div>
<div class="modal fade faq_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>

@endsection
@section('javascript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"></script>
<script src="{{ asset('/assets/jquery-toast-plugin/jquery.toast.min.js') }}"></script>
<script src="{{ asset('/assets/js/custom/function.js') }}"></script>
<script src="{{ asset('/assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('/assets/jquery-toast-plugin/jquery.toast.min.js') }}"></script>
<script src="https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>

<script>
    $(document).ready(function() {
  var form_fields_table = $('#form_fields_table').DataTable({
    processing: true,
    serverSide: true,
    ajax: '/form-fields',
    columns: [
         {
            data: "action",
            name: "action",
            orderable: false,
            searchable: false
        }
      ,
        {
            data: "school_type",
            name: "school_type"
        },
        {
            data: "name",
            name: "name"
        },
        {
            data: "type",
            name: "type"
        },
        {
            data: "is_required",
            name: "is_required"
        },
        {
            data: "default_values",
            name: "default_values"
        },
          {
            data: "rank",
            name: "rank"
        }
       
    ],
    rowReorder: {
        selector: 'td:not(:first-child)', // Allow dragging for all columns except the last one (action column)

        dataSrc: 'rank' // Use the "rank" column as the data source for ordering
    },
  /*  columnDefs: [
        { orderable: true, className: 'rank', targets: 0 }, // Make the rank column sortable
        { orderable: false, targets: '_all' } // Disable sorting for all other columns
    ],*/
    order: [[1, 'asc']] // Default ordering by the second column (index 1)
});

              // Prevent row reorder on action button click
 

            // Handle Row Reordering
    form_fields_table.on('row-reorder', function (e, details, edit) {
           const target = $(e.target);
     

        if (details.length) {
            const rankUpdates = []; // To store reordered rows' data

            details.forEach(function (change) {
                const rowNode = change.node; // Get the row DOM node
                const rowId = $(rowNode).attr('data-id'); // Fetch `data-id` from the DOM
                const newRank = change.newPosition + 1; // Adjust position to start from 1 (optional)
               
                if (rowId) {
                    rankUpdates.push({ id: rowId, new_rank: newRank });
                } else {
                    console.warn('No data-id found for row:', rowNode);
                }
            });

            // Send reordered data to the server
            if (rankUpdates.length) {
                $.ajax({
                    url: '/update-form-fields-rank', // Replace with your endpoint
                    method: 'POST',
                     data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        updates: rankUpdates
                    },
                    success: function (response) {
                        if (response.success) {
                            toastr.success(response.message || 'Rank updated successfully!');
                            form_fields_table.ajax.reload();
                        } else {
                            toastr.error(response.message || 'Failed to update rank.');
                        }
                    },
                    error: function () {
                        toastr.error('An error occurred while updating ranks.');
                    }
                });
            }
        }
    });
            $(document).on('click', 'button.delete_fields_button', function() {
                swal({
                    title: LANG.sure
                    , text: LANG.confirm_delete_role
                    , icon: "warning"
                    , buttons: true
                    , dangerMode: true
                , }).then((willDelete) => {
                    if (willDelete) {
                        var href = $(this).data('href');
                        var data = $(this).serialize();

                        $.ajax({
                            method: "DELETE"
                            , url: href
                            , dataType: "json"
                            , data: data
                            , success: function(result) {
                                if (result.success == true) {
                                    toastr.success(result.msg);
                                    form_fields_table.ajax.reload();
                                } else {
                                    toastr.error(result.msg);
                                }
                            }
                        });
                    }
                });
            });

       
        
        $('#create-form,.create-form,.create-form-without-reset').on('submit', function(e) {
            e.preventDefault();
            let formElement = $(this);
            let formReset = $(this);
            let submitButtonElement = $(this).find(':submit');
            let url = $(this).attr('action');

            
            var school_prefix = '';
            var school_code = '';
           

            let submitButtonText = submitButtonElement.val();
            submitButtonElement.val('Please Wait...').attr('disabled', true);

            setTimeout(() => {
                let data = new FormData(this);
                let preSubmitFunction = $(this).data('pre-submit-function');
                if (preSubmitFunction) {
                    //If custom function name is set in the Form tag then call that function using eval
                    eval(preSubmitFunction + "()");
                }
                let customSuccessFunction = $(this).data('success-function');
                // noinspection JSUnusedLocalSymbols
                function successCallback(response) {

                    if (response.warning) {
                        $('#reset').trigger('click');
                    }

                    if ($(formElement).hasClass('reload-window')) {

                        try {
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        } catch (error) {

                        }
                    }

                    if (!$(formElement).hasClass('create-form-without-reset')) {

                        try {
                            formElement[0].reset();
                        } catch (error) {

                        }
                        $(".select2-dropdown").val("").trigger('change').trigger('unselect');
                        $('.stream-divs').slideUp(500);
                        $('[data-repeater-item]').slice(1).remove();
                    }
                    setTimeout(() => {
                        $('#editModal').modal('hide');
                    }, 500);

                  
                  
                }
                submitButtonElement.val(submitButtonText ? submitButtonText : 'Submit').attr('disabled', false);
                formAjaxRequest('POST', url, data, formElement, submitButtonElement, successCallback);
                form_fields_table.ajax.reload();

            }, 300);
        })

        // Initialize Repeater.js for dynamic fields
        $('.default-values-section').repeater({
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

        // Update the option numbers dynamically
        function updateOptionNumbers() {
            $('.default-values-section .option-section').each(function(index) {
                $(this).find('.option-number').text(index + 1);
            });
        }

        // Show/hide the "default-values-section" based on the selected type
        $('.type-field').on('change', function(e) {
            e.preventDefault();
            const inputValue = $(this).val();
            const optionSection = $('.default-values-section');

            switch (inputValue) {
                case 'dropdown':
                case 'radio':
                case 'checkbox':
                    optionSection.show(500).find('input').attr('required', true);
                    break;
                default:
                    optionSection.hide(500).find('input').removeAttr('required');
                    break;
            }
        });
 $(document).on("click", "button.edit_form_field_button", function() {
            $("div.faq_modal").load($(this).data("href"), function() {
                $(this).modal("show");
  $("form#faq_edit_form").validate({
        rules: {
            name: {
                required: true,
                maxlength: 255,
            },
            type: {
                required: true,
            },
            'default_data.*.option': {
                required: function () {
                    const type = $('#edit-type-select').val();
                    return ['dropdown', 'radio', 'checkbox'].includes(type);
                },
            },
        },
        messages: {
            name: {
                required: "{{ __('The name field is required.') }}",
                maxlength: "{{ __('The name must not exceed 255 characters.') }}",
            },
            type: {
                required: "{{ __('The type field is required.') }}",
            },
            'default_data.*.option': {
                required: "{{ __('Options are required for dropdown, radio, or checkbox types.') }}",
            },
        },
        errorClass: "text-danger", // Add a class for error styling
        highlight: function (element) {
            $(element).closest('.form-group').addClass('has-danger');
        },
        unhighlight: function (element) {
            $(element).closest('.form-group').removeClass('has-danger');
        },
        errorPlacement: function (error, element) {
            if (element.attr("name").startsWith("default_data")) {
                error.appendTo(element.closest('.edit-default-values-section'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
            // Handle AJAX submission here
            var formElement = $(form);
            formElement.find('.has-danger').removeClass("has-danger");
            var formData = new FormData(form);

            $.ajax({
                method: "POST",
                url: formElement.attr("action"),
                data: formData,
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function (xhr) {
                    __disable_submit_button(formElement.find('button[type="submit"]'));
                },
                success: function (result) {
                    if (result.success === true) {
                        $("div.faq_modal").modal("hide");
                        toastr.success(result.msg);
                         form_fields_table.ajax.reload();
                    } else {
                        toastr.error(result.msg);
                    }
                },
                error: function (xhr) {
                    // Handle any server-side validation errors
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        $.each(errors, function (field, messages) {
                            toastr.error(messages.join(", "));
                        });
                    } else {
                        toastr.error("{{ __('An error occurred while processing the request.') }}");
                    }
                },
            });

            return false; // Prevent default form submission
        },
    });
               
            });
        });
      
      


    });
  // Input validation for the "name" field
        function validateInput(event) {
            var charCode = event.which || event.keyCode;
            if (!(charCode >= 65 && charCode <= 90) && !(charCode >= 97 && charCode <= 122) && !(charCode === 32)) {
                event.preventDefault();
            }
        }
        $('input[name="name"]').on('keypress', validateInput);
</script>
@endsection
