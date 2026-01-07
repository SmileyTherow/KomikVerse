<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $subject ?? 'Pesan dari Admin' }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            line-height: 1.6;
            color: #374151;
            margin: 0;
            padding: 40px 20px;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        }

        .email-wrapper {
            max-width: 560px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            padding: 30px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            text-align: center;
        }

        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .email-body {
            padding: 40px;
        }

        .greeting {
            font-size: 18px;
            margin-bottom: 25px;
            color: #4b5563;
        }

        .message-content {
            background: #f9fafb;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            border: 1px solid #e5e7eb;
            white-space: pre-line;
            line-height: 1.7;
            color: #4b5563;
            font-size: 15px;
        }

        .signature {
            margin-top: 40px;
            padding-top: 25px;
            border-top: 1px solid #e5e7eb;
        }

        .admin-name {
            font-weight: 600;
            color: #111827;
            margin-bottom: 5px;
        }

        .admin-title {
            color: #6b7280;
            font-size: 14px;
        }

        .email-footer {
            padding: 25px;
            text-align: center;
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 13px;
        }

        .action-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 8px;
            font-weight: 500;
            margin-top: 20px;
            transition: transform 0.2s;
        }

        .action-button:hover {
            transform: translateY(-2px);
        }

        .icon {
            font-size: 20px;
            margin-right: 8px;
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <!-- Header -->
        <div class="email-header">
            <h1>
                <span>✉️</span>
                {{ $subject ?? 'Pesan Resmi' }}
            </h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p class="greeting">
                Halo <strong style="color: #111827;">{{ $recipientName ?? 'Pengguna' }}</strong>,
            </p>

            <div class="message-content">
                {{ $messageBody }}
            </div>

            <!-- Action Button (jika ada) -->
            @if(isset($actionUrl) && isset($actionText))
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $actionUrl }}" class="action-button">
                    {{ $actionText }}
                </a>
            </div>
            @endif

            <!-- Signature -->
            <div class="signature">
                <div class="admin-name">{{ $adminName ?? 'Admin' }}</div>
                <div class="admin-title">Tim INKOMI</div>
                <div style="margin-top: 15px; font-size: 14px; color: #6b7280;">
                    <span style="display: inline-block; margin-right: 15px;">
                        📧 {{ $adminEmail ?? 'support@inkomi.com' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <div style="margin-bottom: 15px;">
                <a href="#" style="color: #6b7280; text-decoration: none; margin: 0 10px;">Beranda</a>
                •
                <a href="#" style="color: #6b7280; text-decoration: none; margin: 0 10px;">Bantuan</a>
                •
                <a href="#" style="color: #6b7280; text-decoration: none; margin: 0 10px;">Privasi</a>
            </div>
            <p style="margin: 0;">
                © {{ date('Y') }} INKOMI. Jakarta, Indonesia.
            </p>
            <p style="margin: 10px 0 0 0; font-size: 12px; color: #9ca3af;">
                Email ini dikirim oleh sistem. Mohon tidak membalas.
            </p>
        </div>
    </div>
</body>

</html>
