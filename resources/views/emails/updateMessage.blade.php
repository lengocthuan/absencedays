<!doctype html>
<html>
    <head>
        <meta name="viewport" content="width=device-width" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>M2B - Forgot password</title>
        <style>
        /* -------------------------------------
        GLOBAL RESETS
        ------------------------------------- */
        img {
        border: none;
        -ms-interpolation-mode: bicubic;
        max-width: 100%; }
        body {
        font-family: sans-serif;
        -webkit-font-smoothing: antialiased;
        font-size: 21px;
        line-height: 1.4;
        margin: 0;
        padding: 50px;
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%;
        background:#F5F5F5; }
        table {
        border-collapse: separate;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
        width: 100%; }
        table td {
        font-family: sans-serif;
        font-size: 14px;
        vertical-align: top; }
        /* -------------------------------------
        BODY & CONTAINER
        ------------------------------------- */
        .body {
        background-color: #fff;
        width:620px;margin:0px auto;padding: 45px; border:1px solid #e3e3e3;border-radius:4px;}
        /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
        .container {
        display: block;
        Margin: 0 auto !important;
        /* makes it centered */
        width: auto !important;
        width: 580px; }
        /* This should also be a block element, so that it will fill 100% of the .container */
        .content {
        box-sizing: border-box;
        display: block;
        Margin: 0 auto;
        }
        .w3-border {
        border: 1px solid #ccc!important;
        }
        .w3-container, .w3-panel {
        padding: 0.01em 16px;
        }
        .w3-round-xlarge {
        border-radius: 16px;
        }
        
        *, *:before, *:after {
        box-sizing: inherit;
        }
        div {
        display: block;
        }
        /* -------------------------------------
        HEADER, FOOTER, MAIN
        ------------------------------------- */
        .main {
        background: #fff;
        border-radius: 3px;
        line-height: 1.5;
        width: 100%; }
        .center {
        max-width: 600px;
        margin: 0 auto;
        }
        .content-title {
        font-size: 24px;
        text-align: center;
        margin-bottom: 20px;
        }
        .wrapper {
        box-sizing: border-box; }
        .header {
        clear: both;
        margin-bottom: 35px;
        text-align: center;
        width: 100%;
        }
        .footer {
        clear: both;
        padding: 10px 0;
        width: 100%; }
        .footer td,
        .footer p,
        .footer span,
        .footer a {
        color: #333;
        line-height: 1.6;
        font-size: 14px; }
        .footer .contact-link {
        font-size: 15px;
        text-transform: uppercase;
        line-height: 1.9;
        }
        /* -------------------------------------
        TYPOGRAPHY
        ------------------------------------- */
        h1,
        h2,
        h3,
        h4 {
        color: #000000;
        font-family: sans-serif;
        font-weight: 400;
        line-height: 1.4;
        margin: 0;
        Margin-bottom: 15px; }
        h1 {
        font-size: 35px;
        font-weight: 300;
        text-align: center;
        text-transform: capitalize; }
        p,
        ul,
        ol {
        font-family: sans-serif;
        font-size: 16px;
        line-height: 24px;
        font-weight: normal;
        margin: 0;
        Margin-bottom: 15px; }
        p li,
        ul li,
        ol li {
        list-style-position: inside;
        margin-left: 5px; }
        a {
        color: #19ADFF;
        text-decoration: none; }
        /* -------------------------------------
        BUTTONS
        ------------------------------------- */
        .btn {
        text-align: center;
        box-sizing: border-box;
        width: 100%; }
        .btn > tbody > tr > td {
        padding-bottom: 15px; }
        .btn table {
        width: auto; }
        .btn table td {
        background-color: #ffffff;
        border-radius: 5px;
        text-align: center; }
        .btn a {
        background-color: #83cc14;
        border: solid 1px #83cc14;
        border-radius: 5px;
        box-sizing: border-box;
        color: #fff;
        cursor: pointer;
        display: inline-block;
        font-size: 14px;
        font-weight: bold;
        margin: 0;
        padding: 12px 25px;
        text-decoration: none;
        text-transform: capitalize; }
        .btn-primary table td {
        background-color: #83cc14; }
        .btn-primary a {
        background-color: #83cc14;
        border-color: #83cc14;
        color: #ffffff; }
        /* -------------------------------------
        OTHER STYLES THAT MIGHT BE USEFUL
        ------------------------------------- */
        .last {
        margin-bottom: 0; }
        .first {
        margin-top: 0; }
        .align-center {
        text-align: center; }
        .align-right {
        text-align: right; }
        .align-left {
        text-align: left; }
        .clear {
        clear: both; }
        .mt0 {
        margin-top: 0; }
        .mb0 {
        margin-bottom: 0; }
        .preheader {
        color: transparent;
        display: none;
        height: 0;
        max-height: 0;
        max-width: 0;
        opacity: 0;
        overflow: hidden;
        mso-hide: all;
        visibility: hidden;
        width: 0; }
        .powered-by a {
        text-decoration: none; }
        hr {
        border: 0;
        border-bottom: 1px solid #f6f6f6;
        Margin: 20px 0; }
        /* -------------------------------------
        RESPONSIVE AND MOBILE FRIENDLY STYLES
        ------------------------------------- */
        @media only screen and (max-width: 620px) {
        table[class=body] h1 {
        font-size: 28px !important;
        margin-bottom: 10px !important; }
        table[class=body] p,
        table[class=body] ul,
        table[class=body] ol,
        table[class=body] td,
        table[class=body] span,
        table[class=body] a {
        font-size: 16px !important; }
        table[class=body] .wrapper,
        table[class=body] .article {
        padding: 10px !important; }
        table[class=body] .content {
        padding: 0 !important; }
        table[class=body] .container {
        padding: 0 !important;
        width: 100% !important; }
        table[class=body] .main {
        border-left-width: 0 !important;
        border-radius: 0 !important;
        border-right-width: 0 !important; }
        table[class=body] .btn table {
        width: 100% !important; }
        table[class=body] .btn a {
        width: 100% !important; }
        table[class=body] .img-responsive {
        height: auto !important;
        max-width: 100% !important;
        width: auto !important; }
        .footer .content-block {
        padding: 0 10px;
        }}
        /* -------------------------------------
        PRESERVE THESE STYLES IN THE HEAD
        ------------------------------------- */
        @media all {
        .ExternalClass {
        width: 100%; }
        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
        line-height: 100%; }
        .contact-link a {
        color: inherit !important;
        font-family: inherit !important;
        font-size: inherit !important;
        font-weight: inherit !important;
        line-height: inherit !important;
        text-decoration: none !important;
        text-transform: uppercase !important; }
        .btn-primary table td:hover {
        background-color: #34495e !important; }
        .btn-primary a:hover {
        background-color: #34495e !important;
        border-color: #34495e !important; } }
        </style>
    </head>
    <body class="">
        <table border="0" cellpadding="0" cellspacing="0" class="body">
            <tr>
                <td class="container">
                    <div class="content">
                        <!-- START CENTERED WHITE CONTAINER -->
                        <!-- END FOOTER -->
                        <table class="main">
                            <!-- START MAIN CONTENT AREA -->
                            <tr>
                                <td class="wrapper">
                                    <div class="center">
                                        <table border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td>
                                                    <p class="content-title"><b>Thông báo dành cho bảng đăng ký xin {{ $inputs['type_id'] }} của {{ $inputs['register_name'] }}</b></p>
                                                    <div>
                                                        <h2>Xin chào, </h2>
                                                        <h2>Tôi là {{$inputs['name']}},</h2>
                                                        <p>Là một trong những người phê duyệt được bạn {{ $inputs['register_name'] }} yêu cầu.</p>
                                                        <div class="w3-container w3-border w3-round-xlarge" >
                                                            <p>Nội dung xin nghỉ phép của bạn như sau:</p>
                                                            <i><p>Thời gian xin nghỉ phép của bạn gồm các thời gian sau: <br>
                                                                <b>{{ $inputs['timeoff'] }}.</b><br>
                                                            </p>
                                                            <p>Lý do: {{ $inputs['reason'] }}.</p>
                                                            </i>
                                                        </div>
                                                        <p>Sau khi kiểm duyệt tôi muốn thông báo đến bạn như sau:<br>
                                                            <b>{{ $inputs['message'] }}.</b>
                                                        </p>
                                                        @if($inputs['user'] == 1)
                                                        <p>Email này thông báo đến bạn rằng yêu cầu của bạn đã được phê duyệt bởi {{$inputs['approver']}}</p>
                                                        <p>Kèm theo là thông báo đính kèm gửi đến bạn. Hãy đọc nó và tuân thủ nếu có yêu cầu gì trong đó dành cho bạn trong kỳ nghỉ của chính mình.</p>
                                                        <p>Chúc bạn có thời gian nghỉ ngơi thoải mái nhất. Xin chào và hẹn gặp lại bạn.</p>
                                                        <p>Xin cảm ơn bạn đã sử dụng hệ thống.</p>
                                                        @elseif($inputs['user'] == 2)
                                                        <p>Email này thông báo đến bạn rằng yêu cầu của bạn đã <b>không được phê duyệt</b> bởi {{$inputs['approver']}}</p>
                                                        <p>Kèm theo là thông báo đính kèm gửi đến bạn lí do rằng vì sao {{$inputs['name']}} đã không duyệt nghỉ phép cho bạn. Hãy đọc nó và tuân thủ nếu có yêu cầu gì trong đó dành cho bạn.</p>
                                                        <p>Xin chào và hẹn gặp lại bạn.</p>
                                                        <p>Xin cảm ơn bạn đã sử dụng hệ thống.</p>
                                                        @else
                                                        <p>
                                                            <p>Email này thông báo đến bạn rằng bạn <b>phải tiến hành thay đổi thông tin đăng ký nghỉ phép</b> theo thông báo được gửi đến cho bạn từ người phê duyệt {{$inputs['name']}}</p>
                                                            <p>Hãy đọc nó và tuân thủ yêu cầu mà nó đề cập dành cho bạn.</p>
                                                            <p>Sau khi cập nhật xong, hãy chờ cho đến khi người duyệt của bạn gửi thông báo tiếp theo cho bạn</p>
                                                            <p>Chúc bạn có một ngày làm việc hiệu quả.</p>
                                                            <p>Xin cảm ơn bạn đã sử dụng hệ thống.</p>
                                                        </p>
                                                        @endif
                                                        <p class="btn"><a class="button-primary" href="">Xem lại thông tin ngày phép</a></p>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                            <!-- END MAIN CONTENT AREA -->
                        </table>
                        <!-- END CENTERED WHITE CONTAINER -->
                    </div>
                </td>
            </tr>
        </table>
    </body>
</html>