<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $content['subject'] ?? 'Message from ' . $businessName }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f4f4f4;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }

        .wrapper {
            width: 100%;
            padding: 20px 0;
            background: #f4f4f4;
        }

        .card {
            max-width: 520px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }

        .body {
            padding: 32px 36px;
        }

        .greeting {
            margin: 0 0 18px;
            color: #555555;
            font-size: 14px;
            line-height: 1.6;
        }

        .headline {
            margin: 0 0 16px;
            color: #1a1a2e;
            font-size: 22px;
            font-weight: 700;
            line-height: 1.3;
        }

        .message {
            margin: 0 0 8px;
            color: #555555;
            font-size: 14px;
            line-height: 1.7;
        }

        .cta-wrap {
            text-align: center;
            margin: 28px 0 4px;
        }

        .cta-btn {
            display: inline-block;
            color: #ffffff;
            padding: 12px 32px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
        }

        .footer {
            border-top: 1px solid #eeeeee;
            padding: 20px 36px;
            text-align: center;
            background: #fafafa;
        }

        .footer p {
            margin: 0;
            color: #999999;
            font-size: 11px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="card">
            <div
                style="background:{{ $content['accentColor'] ?? '#008aff' }};background:linear-gradient(135deg,{{ $content['accentColor'] ?? '#008aff' }} 0%,{{ $content['accentColor'] ?? '#008aff' }}cc 100%);padding:32px 36px 28px;text-align:center;">
                <p
                    style="margin:0;color:rgba(255,255,255,0.85);font-size:13px;letter-spacing:1px;text-transform:uppercase;">
                    {{ $businessName }}</p>
            </div>
            <div class="body">
                <p class="greeting">Hi <strong>{{ $customerName }}</strong>,</p>
                <h2 class="headline">{{ $content['title'] ?? '' }}</h2>
                <p class="message">{{ $content['message'] ?? '' }}</p>
                @if (!empty($content['buttonText']))
                    <div class="cta-wrap">
                        <a href="#" class="cta-btn"
                            style="background-color:{{ $content['accentColor'] ?? '#008aff' }};">{{ $content['buttonText'] }}</a>
                    </div>
                @endif
            </div>
            <div class="footer">
                <p>&copy; {{ date('Y') }} {{ $businessName }} &middot; All rights reserved</p>
                <p style="margin:4px 0 0;color:#bbbbbb;font-size:10px;">You received this email because you are a valued
                    customer.</p>
            </div>
        </div>
    </div>
</body>

</html>
