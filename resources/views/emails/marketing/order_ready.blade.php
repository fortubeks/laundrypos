<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $content['subject'] ?? 'Your Order is Ready' }}</title>
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

        .header {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            padding: 32px 36px 28px;
            text-align: center;
        }

        .header-brand {
            margin: 0;
            color: rgba(255, 255, 255, 0.85);
            font-size: 13px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .body {
            padding: 32px 36px;
        }

        .icon-wrap {
            text-align: center;
            margin-bottom: 24px;
        }

        .icon-circle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #f5f3ff;
            font-size: 30px;
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
            margin: 0 0 16px;
            color: #555555;
            font-size: 14px;
            line-height: 1.7;
        }

        .info-box {
            background: #f5f3ff;
            border: 1px solid #ddd6fe;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 8px;
        }

        .info-box p {
            margin: 0;
            color: #6d28d9;
            font-size: 13px;
        }

        .cta-wrap {
            text-align: center;
            margin: 28px 0 4px;
        }

        .cta-btn {
            display: inline-block;
            background: #8b5cf6;
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
            <div class="header">
                <p class="header-brand">{{ $businessName }}</p>
            </div>
            <div class="body">
                <div class="icon-wrap">
                    <div class="icon-circle">✅</div>
                </div>
                <p class="greeting">Hi <strong>{{ $customerName }}</strong>,</p>
                <h2 class="headline">{{ $content['title'] ?? 'Your Order is Ready!' }}</h2>
                <p class="message">
                    {{ $content['message'] ?? 'Great news! Your laundry is clean, pressed, and ready for pickup.' }}</p>
                @if (!empty($content['pickupNote']))
                    <div class="info-box">
                        <p>ℹ️ &nbsp;{{ $content['pickupNote'] }}</p>
                    </div>
                @endif
                <div class="cta-wrap">
                    <a href="#" class="cta-btn">{{ $content['buttonText'] ?? 'View Order' }}</a>
                </div>
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
