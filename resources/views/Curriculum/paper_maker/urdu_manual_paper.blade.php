<!DOCTYPE html>

<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Paper</title>
<style>
p{
            font-family: Jameel Noori Nastaleeq;

}
    @media print {
       p{
                   font-family: Jameel Noori Nastaleeq;

       }
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
<link href="{{ asset('assets/css/rtl/rtl_paper.css?v=' . $asset_v) }}"rel="stylesheet" />

<script src="{{ asset('assets/js/jquery.min.js?v=' . $asset_v) }}"></script>

</head>

@php
$question_count = 1;
$page_break_count=0;
$already_break=0;
@endphp


<body class="">
    <button class="print_data" onclick="printData()">Print me</button>

    <div class="page" size="A4" id="page">

    <div class="paper_top">
@include('common.logo_with_height')
                <h3 class="urduText"style="text-align: center;margin:0px;padding:0px;" contenteditable="true"><b>{{$input['paper_head']}}<b></h3>

<h3 style="text-align: center;margin:0px;padding:0px;" contenteditable="true"><b>Paper <strong>{{ $class_subject->name }}</strong> for Class {{ $class_subject->classes->title }}   <b></h3>
        <span class="urduText" style="margin:0px;padding:0px;display: inline;float: left;" class="urduText" > وقت : {{ $input['paper_time'] }}</span>
        <span class="urduText" contenteditable="true" style=" margin:0px;padding:0px;display: inline;float: right;">کل نمبر:
            {{ $input['paper_total_marks'] }}</span>
            
        <div class=" urduText remove_instruction"  style="    direction: rtl;
    
    unicode-bidi: embed;">
                <div>
                    <br><br>
                    <p><span contenteditable="true" class=" urduText"
                            style="margin:0px;padding:0px;display: inline;float: left;">نام:_________________________</span>
                        <span contenteditable="true" class=" urduText"
                            style="margin:0px;padding:0px;display: inline;float: right;">سیکشن:________________________</span>
                    </p>
                    <br><br>
                    <p><span contenteditable="true" class=" urduText"
                            style="margin:0px;padding:0px;display: inline;float: left;">کیمپس:________________________</span>
                        <span contenteditable="true"
                            style="margin:0px;padding:0px;display: inline;float: right;">تاریخ:__________________________</span>
                    </p>
                </div>
                <br><br>
                <div style="border-style: solid;" class=" urduText">
                     
                    <h6 style="text-align:center" class=" urduText">ہدایات برائے طلبہ</h6>
                    <div>
                        <button id="addNewContext" class="menu-item-divided">+</button>
                        <ul>
                            <li contenteditable="true" class=" urduText">پر چہ شروع ہونے سے پہلے اپنا نام ،تاریخ، سیکشن اور کیمپس کا نام ضرور لکھیں۔ <span
                                    style="color:red" class="removeItem">Remove</span></li>
                            <li contenteditable="true" class=" urduText">پر چہ حل کرنے سے پہلے اسے توجہ سے پڑھے اس کے لیے آپ کے پاس 10 منٹ ہیں۔ <span style="color:red"
                                    class="removeItem">Remove</span></li>
                            <li contenteditable="true" class=" urduText">تمام سوالات کے جوابات دی گئی جگہ پر تحریر کیجئے۔ <span
                                    style="color:red" class="removeItem">Remove</span></li>
                            <li contenteditable="true" class=" urduText">جوابات منتخب کر تے ہوئے احتیاط کریں ۔دو جگہ نشان لگانے پر نمبر نہیں ملیں گے۔
                                <span style="color:red" class="removeItem">Remove</span>
                            </li>
                            <li contenteditable="true" class=" urduText"> کراپنا پر چہ نگران کے حوالے کرنے سے پہلے اچھی طرح تسلی کرلیں۔ <span style="color:red" class="removeItem">Remove</span></li>


                        </ul>
                    </div>
                </div>
                <div>
                    <br>
                    <h6 style="text-align:center" class=" urduText">حصہ برائے ممتحن</h6>
<br>
                    <table class="" style=" border-collapse: collapse; line-height:50px">
                        <tbody>
                            <tr>
                                <td class=" urduText"style="border: 1px solid black; "contenteditable="true"> سیکشن <span
                                        style="color:red" class="tableremoveItem">Remove</span> </td>
                                <td class=" urduText"style="border: 1px solid black;" contenteditable="true"> سیکشن الف <span>15</span>
                                    <span style="color:red" class="tableremoveItem">Remove</span>
                                </td>
                                <td class=" urduText"style="border: 1px solid black;" contenteditable="true"> سیکشن ب <span>15</span>
                                    <span style="color:red" class="tableremoveItem">Remove</span>
                                </td>
                                <td class=" urduText"style="border: 1px solid black;" contenteditable="true"> سیکشن ج <span>15</span>
                                    <span style="color:red" class="tableremoveItem">Remove</span>
                                </td>
                                <td class=" urduText"style="border: 1px solid black;" contenteditable="true"> کُل نمبرات <span
                                        style="color:red" class="tableremoveItem">Remove</span></td>
                            </tr>
                            <tr>
                                <td class=" urduText" style="border: 1px solid black;" contenteditable="true"> سوال نمبر <span style="color:red"
                                        class="tableremoveItem">Remove</span></td>
                                <td class=" urduText" style="border: 1px solid black;"> <span style="color:red"
                                        class="tableremoveItem">Remove</span>
                                    <input class="input_q" oninput="generateTable(this)"></input>
                                    <table class="dynamicTable" style="border-collapse: collapse;"></table>
                                </td>
                                <td class=" urduText" style="border: 1px solid black;"><span style="color:red"
                                        class="tableremoveItem">Remove</span>

                                    <input class="input_q" oninput="generateTable(this)"></input>
                                    <table class="dynamicTable" style="border-collapse: collapse;"></table>
                                </td>
                                <td class=" urduText" style="border: 1px solid black;"><span style="color:red"
                                        class="tableremoveItem">Remove</span>

                                    <input class="input_q" oninput="generateTable(this)"></input>
                                    <table class="dynamicTable" style="border-collapse: collapse;"></table>
                                </td>
                                <td class=" urduText" style="border: 1px solid black;" contenteditable="true"><span style="color:red"
                                        class="tableremoveItem">Remove</span></td>
                            </tr>
                            <tr>
                                <td class=" urduText" style="border: 1px solid black;"> کُل نمبر <span style="color:red"
                                        class="tableremoveItem">Remove</span></td>
                                <td class=" urduText" style="border: 1px solid black;"> <span style="color:red"
                                        class="tableremoveItem">Remove</span>
                                    <input class="input_q" oninput="generateTable(this)"></input>
                                    <table class="dynamicTable" style="border-collapse: collapse;"></table>
                                </td>
                                <td class=" urduText" style="border: 1px solid black;"> <span style="color:red"
                                        class="tableremoveItem">Remove</span> <input class="input_q"
                                        oninput="generateTable(this)"></input>
                                    <table class="dynamicTable" style="border-collapse: collapse;"><span
                                            style="color:red" class="tableremoveItem">Remove</span></table>
                                </td>
                                <td class=" urduText" style="border: 1px solid black;"> <span style="color:red"
                                        class="tableremoveItem">Remove</span> <input class="input_q"
                                        oninput="generateTable(this)"></input>
                                    <table class="dynamicTable" style="border-collapse: collapse;"></table>
                                </td>
                                <td class=" urduText" style="border: 1px solid black;" contenteditable="true"> {{ $input['paper_total_marks'] }}<span style="color:red"
                                        class="tableremoveItem">Remove</span></td>
                            </tr>

                            <tr>
                                <td class=" urduText"style="border: 1px solid black;">حاصل کردہ نمبر <span style="color:red"
                                        class="tableremoveItem">Remove</span></td>
                                <td class=" urduText" style="border: 1px solid black;"><span style="color:red"
                                        class="tableremoveItem">Remove</span>
                                    <input class="empty_input_q" oninput="emptygenerateTable(this)"></input>
                                    <table class="dynamicTable" style="border-collapse: collapse;"></table>
                                </td>
                                <td class=" urduText" style="border: 1px solid black;"> <span style="color:red"
                                        class="tableremoveItem">Remove</span> <input class="empty_input_q"
                                        oninput="emptygenerateTable(this)"></input>
                                    <table class="dynamicTable" style="border-collapse: collapse;"></table>
                                </td>
                                <td class=" urduText" style="border: 1px solid black;">
                                    <span style="color:red" class="tableremoveItem">Remove</span><input
                                        class="empty_input_q" oninput="emptygenerateTable(this)"></input>
                                    <table class="dynamicTable" style="border-collapse: collapse;"></table>
                                </td>
                                <td class=" urduText" style="border: 1px solid black;" contenteditable="true"><span style="color:red"
                                        class="tableremoveItem">Remove</span></td>
                            </tr>



                        </tbody>
                    </table>
                    <br>
                    <br>
                    <p><span contenteditable="true"
                            style="margin:0px;padding:0px;display: inline;float: left;" class=" urduText">دستخط نگران:________________________</span>
                        <span contenteditable="true"
                            style="margin:0px;padding:0px;display: inline;float: right;" class=" urduText">دستخط ممتحن:__________________________</span>
                    </p>
                    <br>
                    <br>
                       <br>   <br>
                    <p><span contenteditable="true"
                            style="margin:0px;padding:0px;display: inline;float: left;" class=" urduText">دستخط جانچ کنندہ:________________________</span>

                    </p>
                </div>

                <p class="breakp"></p>
            </div>
    </div>

    @include('Curriculum.paper_maker.ltr_print_partials.mcq')
    @include('Curriculum.paper_maker.ltr_print_partials.fill_in_the_blank')
    @include('Curriculum.paper_maker.ltr_print_partials.true_and_false')
    @include('Curriculum.paper_maker.print_partials.column_matching')
    @include('Curriculum.paper_maker.print_partials.words_and_use')
    @include('Curriculum.paper_maker.print_partials.paraphrase')
    @include('Curriculum.paper_maker.print_partials.passage')
    @include('Curriculum.paper_maker.print_partials.stanza')
    @include('Curriculum.paper_maker.ltr_print_partials.short')
    @include('Curriculum.paper_maker.ltr_print_partials.long')
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

