<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Update: Your Document is Moving</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7fa; color: #333333; line-height: 1.6; }
        .email-wrapper { width: 100%; background-color: #f4f7fa; padding: 40px 20px; }
        .email-container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); }
        .email-header { background: linear-gradient(135deg, #9333ea 0%, #a855f7 100%); padding: 40px 30px; text-align: center; color: #ffffff; }
        .email-header img { max-width: 80px; margin-bottom: 20px; }
        .email-header h1 { margin: 0; font-size: 26px; font-weight: 600; letter-spacing: -0.5px; }
        .email-header p { margin: 10px 0 0 0; font-size: 14px; opacity: 0.9; }
        .email-body { padding: 40px 30px; }
        .greeting { font-size: 18px; font-weight: 600; color: #9333ea; margin-bottom: 20px; }
        .message { font-size: 15px; line-height: 1.8; color: #555555; margin-bottom: 25px; }
        .document-details { background-color: #faf5ff; border-left: 4px solid #9333ea; padding: 20px; border-radius: 8px; margin: 25px 0; }
        .detail-row { display: table; width: 100%; padding: 10px 0; border-bottom: 1px solid #e9d5ff; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { display: table-cell; font-weight: 600; color: #374151; width: 140px; padding-right: 15px; vertical-align: top; }
        .detail-value { display: table-cell; color: #555555; vertical-align: top; }
        .progress-path { background-color: #f3e8ff; border-radius: 8px; padding: 15px; margin: 20px 0; text-align: center; }
        .progress-path-text { color: #6b21a8; font-size: 14px; font-weight: 600; }
        .progress-arrow { color: #9333ea; font-size: 20px; margin: 0 10px; }
        .status-badge { display: inline-block; background-color: #f3e8ff; color: #9333ea; padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; margin-bottom: 20px; }
        .notes-box { background-color: #fef3c7; border: 1px solid #fde68a; border-radius: 8px; padding: 15px; margin: 20px 0; }
        .notes-box strong { color: #92400e; display: block; margin-bottom: 8px; font-size: 14px; }
        .notes-box p { color: #78350f; margin: 0; font-size: 14px; line-height: 1.6; font-style: italic; }
        .info-box { background-color: #faf5ff; border: 1px solid #e9d5ff; border-radius: 8px; padding: 15px; margin: 20px 0; text-align: center; }
        .info-box p { color: #581c87; margin: 0; font-size: 14px; line-height: 1.6; }
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
            .progress-path-text { font-size: 12px; }
            .progress-arrow { font-size: 16px; margin: 0 5px; }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Header -->
            <div class="email-header">
                <h1>Document Update: Your Document is Moving</h1>
                <p>Document Tracking System</p>
            </div>

            <!-- Body -->
            <div class="email-body">
                <div class="greeting">Hello {{ $notifiable->name }}!</div>
                
                <div style="text-align: center;">
                    <span class="status-badge">Processing</span>
                </div>

                <div class="message">
                    Your document is being processed and has been forwarded to another unit. Here's the latest update on its progress.
                </div>

                <!-- Progress Path -->
                <div class="progress-path">
                    <div class="progress-path-text">
                        {{ $forwardHistory->fromUnit->name }}
                        <span class="progress-arrow">➜</span>
                        {{ $forwardHistory->toUnit->name }}
                    </div>
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
                        <div class="detail-label">Current Location:</div>
                        <div class="detail-value">{{ $forwardHistory->fromUnit->name }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Forwarded to:</div>
                        <div class="detail-value">{{ $forwardHistory->toUnit->name }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Forwarded by:</div>
                        <div class="detail-value">{{ $forwardHistory->forwardedBy->name }}</div>
                    </div>
                </div>

                <!-- Forwarding Notes -->
                @if($forwardHistory->notes)
                    <div class="notes-box">
                        <strong>📝 Notes:</strong>
                        <p>{{ $forwardHistory->notes }}</p>
                    </div>
                @endif

                <!-- Info Box -->
                <div class="info-box">
                    <p>Your document is moving through the workflow. You will be notified of any updates.</p>
                </div>

                <!-- Signature -->
                <div class="signature">
                    <p style="margin: 0; color: #374151;">Regards,</p>
                    <p style="margin: 5px 0 0 0; color: #9333ea; font-weight: 600;">Document Tracking System</p>
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
