<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Received</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7fa; color: #333333; line-height: 1.6; }
        .email-wrapper { width: 100%; background-color: #f4f7fa; padding: 40px 20px; }
        .email-container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); }
        .email-header { background: linear-gradient(135deg, #059669 0%, #10b981 100%); padding: 40px 30px; text-align: center; color: #ffffff; }
        .email-header img { max-width: 80px; margin-bottom: 20px; }
        .email-header h1 { margin: 0; font-size: 26px; font-weight: 600; letter-spacing: -0.5px; }
        .email-header p { margin: 10px 0 0 0; font-size: 14px; opacity: 0.9; }
        .email-body { padding: 40px 30px; }
        .greeting { font-size: 18px; font-weight: 600; color: #059669; margin-bottom: 20px; }
        .message { font-size: 15px; line-height: 1.8; color: #555555; margin-bottom: 25px; }
        .document-details { background-color: #f0fdf4; border-left: 4px solid #059669; padding: 20px; border-radius: 8px; margin: 25px 0; }
        .detail-row { display: table; width: 100%; padding: 10px 0; border-bottom: 1px solid #d1fae5; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { display: table-cell; font-weight: 600; color: #374151; width: 140px; padding-right: 15px; vertical-align: top; }
        .detail-value { display: table-cell; color: #555555; vertical-align: top; }
        .success-badge { display: inline-block; background-color: #d1fae5; color: #059669; padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; margin-bottom: 20px; }
        .email-footer { background-color: #f8f9fc; padding: 30px; text-align: center; border-top: 1px solid #e5e7eb; }
        .email-footer p { margin: 8px 0; font-size: 13px; color: #6b7280; line-height: 1.5; }
        .signature { margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 14px; color: #6b7280; }
        @media only screen and (max-width: 600px) {
            .email-wrapper { padding: 20px 10px; }
            .email-header, .email-body, .email-footer { padding: 30px 20px; }
            .email-header h1 { font-size: 22px; }
            .detail-row { display: block; }
            .detail-label { display: block; margin-bottom: 5px; width: 100%; }
            .detail-value { display: block; width: 100%; }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Header -->
            <div class="email-header">
                <h1>Document Received</h1>
                <p>Document Tracking System</p>
            </div>

            <!-- Body -->
            <div class="email-body">
                <div class="greeting">Hello {{ $user->name }}!</div>
                
                <div style="text-align: center;">
                    <span class="success-badge">✓ Successfully Received</span>
                </div>

                <div class="message">
                    Great news! Your document has been successfully received by the receiving unit.
                </div>

                <!-- Document Details Card -->
                <div class="document-details">
                    <div class="detail-row">
                        <div class="detail-label">Document No:</div>
                        <div class="detail-value"><strong>{{ $document->document_number }}</strong></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Title:</div>
                        <div class="detail-value">{{ $document->title }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">From:</div>
                        <div class="detail-value">{{ $document->senderUnit->name ?? 'Unknown Unit' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Received by:</div>
                        <div class="detail-value">{{ $receivedBy->name }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Date & Time:</div>
                        <div class="detail-value">{{ $document->received_at->format('F j, Y g:i A') }}</div>
                    </div>
                </div>

                <!-- Signature -->
                <div class="signature">
                    <p style="margin: 0; color: #374151;">Thank you for using our document tracking system!</p>
                    <p style="margin: 5px 0 0 0; color: #059669; font-weight: 600;">Document Tracking System</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="email-footer">
                <p>This is an automated notification from the Document Tracking System.</p>
                <p>Please do not reply to this email.</p>
                <p style="margin-top: 15px; font-size: 12px; color: #9ca3af;">
                    &copy; {{ date('Y') }} Document Tracking System. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
