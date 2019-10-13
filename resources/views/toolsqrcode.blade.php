<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>输入文字生成二维码</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 40px;
            }

            .m-b-md {
                margin-top: 10px;
            }

            .button {
                padding: 0 60px;
                font-size: 30px;
                height: 60px;
                background-color:#39b54a;
                color:#fff;
            }
        </style>
    </head>
    <body>
        <div class=" position-ref full-height">
            <div class="content">
                <div style="display:none;width:200px;height:200px;text-align: center;margin:0 auto" id="image">

                </div>
                <div class="title m-b-md">
                    输入文字生成二维码
                </div>
                <form>
                    <div>
                        <textarea rows="10" cols="80" id="content"></textarea>
                    </div>
                    <input class='button' type="button" value="提交" id="button"/>
                 </form>

            </div>
        </div>
    </body>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var tools_type = '{{$tools_type}}'
        $('#button').click(function () {
            $('#image').hide();
            var content = $('#content').val()
            if (!content) {
                window.alert('请输入文字')
            } else {
                $.post({
                    url: '/tools',
                    data: {content:content, tools_type:tools_type},
                    success(data) {
                        $('#image').show();
                        $('#image').html(data)
                    }
                })
            }
        })
    </script>
</html>
