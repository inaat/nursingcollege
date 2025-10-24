@extends('frontend.layouts.school.master')
@section('title')
Search Result
@endsection
@section('content')
<div class="breadcrumb">
    <div class="container">
        <div class="contentWrapper">
            <span class="title"> Search Result</span>
            <span>
                <a href="{{ url('/') }}" class="home">Home</a>
                <span><i class="fa-solid fa-caret-right"></i></span>
                <span class="page">Search Result</span>
            </span>
        </div>
    </div>
</div>

<section class="contactUs commonMT commonWaveSect">
    <div class="container card p-4 shadow-lg rounded " >
        <div class="row">
            <div class="col-lg-12">
              <div class="headlines text-center">
                        <span>Check Results</span>
                        <span>Here Check Your Recent Result!?</span>
                    </div>
                <div class="formWrapper center">
                        {!! Form::open(['url' => action('Frontend\FrontHomeController@getResult'), 'method' => 'get', 'id' =>'result_get_form' ,'files' => true]) !!}

                        @csrf
                        <div class="row g-3">
                            <!-- Select Session -->
                            <div class="col-md-4 p-1">
                                {!! Form::label('english.sessions', __('english.sessions') . ':*') !!}
                                {!! Form::select('session_id',$sessions,null, ['class' => 'form-select select2 ','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select'),'id'=>'exam-session']) !!}
                            </div>
                            <div class="col-md-4 p-1">
                       {!! Form::label('term', __( 'english.term' ) . ':*') !!}
                       {!! Form::select('exam_create_id',[],null, ['class' => 'form-select select2 exam_create_id exam_term_id','required',  'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                            </div>

                          

                            <!-- Input Roll Number -->
                            <div class="col-lg-4">
                                <label for="rollno" class="form-label fw-semibold">Enter Roll Number:*</label>
                                <input type="text" class="form-control" id="rollno" required,  name="rollno" placeholder="Roll Number" required>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-lg-12 text-center mt-4">
                                <button type="submit" class="commonBtn px-5 py-2 fw-bold shadow">
                                    Check Result
                                </button>
                            </div>
                        </div>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
        
                    <div class="row">
                        <div class="col-md-12">

                            <div id="contact_ledger_div"></div>


                        </div>
                    </div>
    </div>
</section>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('/assets/js/custom/common.js') }}"></script>
<script src="{{ asset('/assets/js/custom/custom.js') }}"></script>
<script src="{{ asset('/assets/js/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('/assets/js/jquery.validate.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Initialize Select2
     $('.select2').select2({

          theme: "bootstrap-5",
    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
    placeholder: $( this ).data( 'placeholder' ),
  
          // Forces the dropdown width to match the parent
       
        });
    });
    
    $.ajaxSetup({
        beforeSend: function(jqXHR, settings) {
            
            if (settings.url.indexOf('http') === -1) {
                settings.url = base_path + settings.url;
            }
        },
    });

    $(document).on('change', '#exam-session', function () {
    // Reference to the changed element
    var doc = $(this);
    
    // Retrieve values for campus_id and session_id
    var session_id = doc.val(); // Get the selected value of the session dropdown
    
    // Perform AJAX request to fetch term
    $.ajax({
        method: "GET",
        url: "/get_term",
        dataType: "html",
        data: {
            session_id: session_id
        },
        success: function (result) {
            if (result) {
                // Update the exam term dropdown with the result
                doc.closest(".row").find(".exam_term_id").html(result);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error fetching term:", error);
        }
    });

 
     $(document).on('submit', 'form#result_get_form', function(e) {
        e.preventDefault();
        var form = $(this);
        var data = form.serialize();

        $.ajax({
            method: 'GET',
            url: $(this).attr('action'),
            dataType: 'html',
            data: data,
            beforeSend: function(xhr) {
                __disable_submit_button(form.find('button[type="submit"]'));
            },
            success: function(result) {
                 $('#contact_ledger_div').html(result); // Update the ledger div with the result

            },
        });
    });
    function __disable_submit_button(element) {
    
        element.attr('disable', true);
    
}
});

</script>
@endsection
