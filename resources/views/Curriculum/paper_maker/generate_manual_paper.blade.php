<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@lang('english.paper')</title>
<style>
    @media print {

        #addNewContext,
        .removeItem,
        .tableremoveItem,
        .empty_input_q {
            display: none;
        }

        .breakp {
            display: block;
            page-break-before: always;
        }

        .input_q {
            display: none;
        }
    }
</style>
<link href="{{ asset('assets/css/rtl/paper.css?v=' . $asset_v) }}"rel="stylesheet" />
<link href="{{ asset('/js/tinymce/matheditor/html/css/math.css') }}" rel="stylesheet" />
<script src="{{ asset('assets/js/jquery.min.js?v=' . $asset_v) }}"></script>

</head>
@php
    $question_count = 1;
    $page_break_count = 0;
    $already_break = 0;
@endphp


<body>
    <button class="print_data" onclick="printData()">Print me</button>

    <div class="page" size="A4" id="page">
        <div class="paper_top">
            @include('common.logo_with_height')
            <h3 contenteditable="true"style="text-align: center;margin:0px;padding:0px;">
                <b>{{ $input['paper_head'] }}<b>
            </h3>
            <h3 contenteditable="true" style="text-align: center;margin:0px;padding:0px;"><b>Paper
                    <strong>{{ $class_subject->name }}</strong> for Class {{ $class_subject->classes->title }} <b></h3>

            <span contenteditable="true" style="margin:0px;padding:0px;display: inline;float: left;">Time:
                {{ $input['paper_time'] }}</span>
            <span contenteditable="true" style="margin:0px;padding:0px;display: inline;float: right;">@lang('english.total_marks'):
                {{ $input['paper_total_marks'] }}</span>
            <div class="remove_instruction">
                <div>
                    <br><br>
                    <p><span contenteditable="true"
                            style="margin:0px;padding:0px;display: inline;float: left;">Name:_________________________</span>
                        <span contenteditable="true"
                            style="margin:0px;padding:0px;display: inline;float: right;">Section:________________________</span>
                    </p>
                    <br><br>
                    <p><span contenteditable="true"
                            style="margin:0px;padding:0px;display: inline;float: left;">Campus:________________________</span>
                        <span contenteditable="true"
                            style="margin:0px;padding:0px;display: inline;float: right;">Date:__________________________</span>
                    </p>
                </div>
                <br><br>
                <div style="border-style: solid;">
                     
                    <h6 style="text-align:center">INSTRUCTIONS FOR STUDENTS</h6>
                    <div>
                        <button id="addNewContext" class="menu-item-divided">+</button>
                        <ul>
                            <li contenteditable="true">Make sure Name, Campus and Date is filled in. <span
                                    style="color:red" class="removeItem">Remove</span></li>
                            <li contenteditable="true">Use 10 minutes to read through the paper. <span style="color:red"
                                    class="removeItem">Remove</span></li>
                            <li contenteditable="true">This questions paper consists of two parts.Part I and II. <span
                                    style="color:red" class="removeItem">Remove</span></li>
                            <li contenteditable="true">Solve answer of question given in part I and II in space
                                provided.
                                <span style="color:red" class="removeItem">Remove</span>
                            </li>
                            <li contenteditable="true">Check your answer sheet carefully before you hand it over to the
                                invigilator. <span style="color:red" class="removeItem">Remove</span></li>


                        </ul>
                    </div>
                </div>
                <div>
                    <br>
                    <h6 style="text-align:center">For teacher's use only</h6>
<br>
                    <table class="" style=" border-collapse: collapse; line-height:50px">
                        <tbody>
                            <tr>
                                <td style="border: 1px solid black; "contenteditable="true"> Sections <span
                                        style="color:red" class="tableremoveItem">Remove</span> </td>
                                <td style="border: 1px solid black;" contenteditable="true"> Sections A <span>15</span>
                                    <span style="color:red" class="tableremoveItem">Remove</span>
                                </td>
                                <td style="border: 1px solid black;" contenteditable="true"> Sections B <span>15</span>
                                    <span style="color:red" class="tableremoveItem">Remove</span>
                                </td>
                                <td style="border: 1px solid black;" contenteditable="true"> Sections C <span>15</span>
                                    <span style="color:red" class="tableremoveItem">Remove</span>
                                </td>
                                <td style="border: 1px solid black;" contenteditable="true"> Total Marks <span
                                        style="color:red" class="tableremoveItem">Remove</span></td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid black;" contenteditable="true"> QNo <span style="color:red"
                                        class="tableremoveItem">Remove</span></td>
                                <td style="border: 1px solid black;"> <span style="color:red"
                                        class="tableremoveItem">Remove</span>
                                    <input class="input_q" oninput="generateTable(this)"></input>
                                    <table class="dynamicTable" style="border-collapse: collapse;"></table>
                                </td>
                                <td style="border: 1px solid black;"><span style="color:red"
                                        class="tableremoveItem">Remove</span>

                                    <input class="input_q" oninput="generateTable(this)"></input>
                                    <table class="dynamicTable" style="border-collapse: collapse;"></table>
                                </td>
                                <td style="border: 1px solid black;"><span style="color:red"
                                        class="tableremoveItem">Remove</span>

                                    <input class="input_q" oninput="generateTable(this)"></input>
                                    <table class="dynamicTable" style="border-collapse: collapse;"></table>
                                </td>
                                <td style="border: 1px solid black;" contenteditable="true"><span style="color:red"
                                        class="tableremoveItem">Remove</span></td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid black;"> Total Marks <span style="color:red"
                                        class="tableremoveItem">Remove</span></td>
                                <td style="border: 1px solid black;"> <span style="color:red"
                                        class="tableremoveItem">Remove</span>
                                    <input class="input_q" oninput="generateTable(this)"></input>
                                    <table class="dynamicTable" style="border-collapse: collapse;"></table>
                                </td>
                                <td style="border: 1px solid black;"> <span style="color:red"
                                        class="tableremoveItem">Remove</span> <input class="input_q"
                                        oninput="generateTable(this)"></input>
                                    <table class="dynamicTable" style="border-collapse: collapse;"><span
                                            style="color:red" class="tableremoveItem">Remove</span></table>
                                </td>
                                <td style="border: 1px solid black;"> <span style="color:red"
                                        class="tableremoveItem">Remove</span> <input class="input_q"
                                        oninput="generateTable(this)"></input>
                                    <table class="dynamicTable" style="border-collapse: collapse;"></table>
                                </td>
                                <td style="border: 1px solid black;" contenteditable="true"> {{ $input['paper_total_marks'] }}<span style="color:red"
                                        class="tableremoveItem">Remove</span></td>
                            </tr>

                            <tr>
                                <td style="border: 1px solid black;">Marks Obtained <span style="color:red"
                                        class="tableremoveItem">Remove</span></td>
                                <td style="border: 1px solid black;"><span style="color:red"
                                        class="tableremoveItem">Remove</span>
                                    <input class="empty_input_q" oninput="emptygenerateTable(this)"></input>
                                    <table class="dynamicTable" style="border-collapse: collapse;"></table>
                                </td>
                                <td style="border: 1px solid black;"> <span style="color:red"
                                        class="tableremoveItem">Remove</span> <input class="empty_input_q"
                                        oninput="emptygenerateTable(this)"></input>
                                    <table class="dynamicTable" style="border-collapse: collapse;"></table>
                                </td>
                                <td style="border: 1px solid black;">
                                    <span style="color:red" class="tableremoveItem">Remove</span><input
                                        class="empty_input_q" oninput="emptygenerateTable(this)"></input>
                                    <table class="dynamicTable" style="border-collapse: collapse;"></table>
                                </td>
                                <td style="border: 1px solid black;" contenteditable="true"><span style="color:red"
                                        class="tableremoveItem">Remove</span></td>
                            </tr>



                        </tbody>
                    </table>
                    <br>
                    <br>
                    <p><span contenteditable="true"
                            style="margin:0px;padding:0px;display: inline;float: left;">Invigilated
                            By:________________________</span>
                        <span contenteditable="true"
                            style="margin:0px;padding:0px;display: inline;float: right;">Marked
                            By:__________________________</span>
                    </p>
                    <br>
                    <br>
                       <br>   <br>
                    <p><span contenteditable="true"
                            style="margin:0px;padding:0px;display: inline;float: left;">Rechecked
                            By:________________________</span>

                    </p>
                </div>

                <p class="breakp"></p>
            </div>
        </div>
        @include('Curriculum.paper_maker.print_partials.mcq')
        @include('Curriculum.paper_maker.print_partials.fill_in_the_blank')
        @include('Curriculum.paper_maker.print_partials.true_and_false')
        @include('Curriculum.paper_maker.print_partials.column_matching')
        @include('Curriculum.paper_maker.print_partials.words_and_use')
        @include('Curriculum.paper_maker.print_partials.paraphrase')
        @include('Curriculum.paper_maker.print_partials.passage')
        @include('Curriculum.paper_maker.print_partials.stanza')
        @include('Curriculum.paper_maker.print_partials.short')
        @include('Curriculum.paper_maker.print_partials.long')
        @include('Curriculum.paper_maker.print_partials.translation_to_urdu')
        @include('Curriculum.paper_maker.print_partials.translation_to_english')
        @include('Curriculum.paper_maker.print_partials.grammar')
        @include('Curriculum.paper_maker.print_partials.contextual')
        @include('Curriculum.paper_maker.print_partials.singular_and_plural')
        @include('Curriculum.paper_maker.print_partials.masculine_and_feminine')


    </div>



    <script>
        function printData() {
            checkPto('mcq_pto');
            checkPto('fill_pto');
            checkPto('true_pto');
            checkPto('short_pto');
            checkPto('long_pto');


            window.print();
        }

        function checkPto(idpto) {
            var checkpto = $('#' + idpto);

            if (checkpto.text().length > 1 && checkpto) {
                checkpto.closest(".find").find(".custom_break").addClass("pagebreak");;
            }

        }
        $('#addNewContext')

            // on click, make content editable.
            .click(function() {
                alert(555);
                $('ul').append(
                    '<li contenteditable="true">New item <span style="color:red" class="removeItem">Remove</span></li>'
                );

            });
        // Remove li on remove button click
        $('ul').on('click', '.removeItem', function() {
            $(this).closest('li').remove();
        });



        function generateTable(inputElement) {
            var rowCount = $(inputElement).val();
            var table = $(inputElement).closest('td').find('.dynamicTable');
            table.empty();
            var row = $('<tr>');
            for (var i = 1; i <= rowCount; i++) {

                row.append($('<td style="border: 1px solid black;" contenteditable="true">' + i + '</td>'));

            }
            table.append(row);
        }

        function emptygenerateTable(inputElement) {
            var rowCount = $(inputElement).val();
            var table = $(inputElement).closest('td').find('.dynamicTable');
            table.empty();
            var row = $('<tr>');
            for (var i = 1; i <= rowCount; i++) {

                row.append($('<td style="border: 1px solid black;" contenteditable="true">' + '&nbsp;' + '</td>'));

            }
            table.append(row);
        }
        // Remove li on remove button click
        $('table').on('click', '.tableremoveItem', function() {
            $(this).closest('td').remove();
        });
    </script>
</body>

</html>
