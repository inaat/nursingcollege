<div class="modal-dialog" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action([App\Http\Controllers\SessionController::class, 'postChangePassword']), 'method' => 'post', 'id' => 'change_password_form' ]) !!}

        <div class="modal-header bg-primary text-white">
            <h4 class="modal-title">Change Password</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="form-group">
                {!! Form::label('current_password', __('Current Password') . ":*") !!}
                {!! Form::password('current_password', ['class' => 'form-control', 'required', 'placeholder' => __('Enter current password') ]) !!}
            </div>

            <div class="form-group mt-3">
                {!! Form::label('new_password', __('New Password') . ":*") !!}
                {!! Form::password('new_password', ['class' => 'form-control', 'required', 'placeholder' => __('Enter new password') ]) !!}
            </div>

            <div class="form-group mt-3">
                {!! Form::label('confirm_password', __('Confirm Password') . ":*") !!}
                {!! Form::password('confirm_password', ['class' => 'form-control', 'required', 'placeholder' => __('Confirm new password') ]) !!}
            </div>
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang('messages.submit')</button>
            <button type="button" class="btn btn-default" data-bs-dismiss="modal">@lang('english.close')</button>
        </div>

        {!! Form::close() !!}

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script>
    $(document).ready(function () {

        $("#change_password_form").validate({
            rules: {
                current_password: {
                    required: true,
                },
                new_password: {
                    required: true,
                    minlength: 8,
                    regex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/
                },
                new_password_confirmation: {
                    required: true,
                    equalTo: "#new_password"
                }
            },
            messages: {
                current_password: {
                    required: "Please enter your current password.",
                },
                new_password: {
                    required: "Please enter a new password.",
                    minlength: "Your password must be at least 8 characters long.",
                    regex: "Password must contain at least one uppercase letter, one lowercase letter, and one number."
                },
                new_password_confirmation: {
                    required: "Please confirm your new password.",
                    equalTo: "Passwords do not match."
                }
            },
            errorElement: "div",
            errorClass: "text-danger",
            highlight: function (element) {
                $(element).addClass("is-invalid");
            },
            unhighlight: function (element) {
                $(element).removeClass("is-invalid");
            },
            submitHandler: function (form) {
                form.submit();
            }
        });

        // Custom rule for password regex validation
        $.validator.addMethod("regex", function (value, element, regexpr) {
            return regexpr.test(value);
        });
                    $('.pay_fee_due_modal').find('form#pay_student_due_form').validate();

    });

</script>
