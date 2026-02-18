<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overdue Document Alert</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7fa; color: #333333; line-height: 1.6; }
        .email-wrapper { width: 100%; background-color: #f4f7fa; padding: 40px 20px; }
        .email-container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); }
        .email-header { background: linear-gradient(135deg, #b91c1c 0%, #dc2626 100%); padding: 40px 30px; text-align: center; color: #ffffff; }
        .email-header h1 { margin: 0; font-size: 26px; font-weight: 600; letter-spacing: -0.5px; }
        .email-header p { margin: 10px 0 0 0; font-size: 14px; opacity: 0.9; }
        .email-body { padding: 40px 30px; }
        .greeting { font-size: 18px; font-weight: 600; color: #b91c1c; margin-bottom: 20px; }
        .message { font-size: 15px; line-height: 1.8; color: #555555; margin-bottom: 25px; }
        .warning-box { background-color: #fef2f2; border-left: 4px solid #dc2626; padding: 15px; border-radius: 8px; margin: 20px 0; color: #991b1b; font-size: 14px; font-weight: 600; }
        .document-details { background-color: #fef2f2; border-left: 4px solid #dc2626; padding: 20px; border-radius: 8px; margin: 25px 0; }
        .detail-row { display: table; width: 100%; padding: 10px 0; border-bottom: 1px solid #fecaca; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { display: table-cell; font-weight: 600; color: #374151; width: 140px; padding-right: 15px; vertical-align: top; }
        .detail-value { display: table-cell; color: #555555; vertical-align: top; }
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
            <div class="email-header">
                <h1>Overdue Document Alert</h1>
                <p>Document Tracking System</p>
            </div>

            <div class="email-body">
                <div class="greeting">Hello {{ $user->name }}!</div>

                <div class="message">
                    An incoming document in your unit has been pending for {{ $pendingDays }} days and is now overdue.
                </div>

                <div class="warning-box">
                    Action required: This document is overdue by {{ $overdueDays }} day{{ $overdueDays === 1 ? '' : 's' }} beyond the 3-day limit.
                </div>

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
                        <div class="detail-label">Type:</div>
                        <div class="detail-value">{{ $document->document_type }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">From:</div>
                        <div class="detail-value">{{ $document->senderUnit->name ?? 'Unknown Unit' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">To Unit:</div>
                        <div class="detail-value">{{ $document->receivingUnit->name ?? 'Unknown Unit' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Pending Days:</div>
                        <div class="detail-value">{{ $pendingDays }} day{{ $pendingDays === 1 ? '' : 's' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Overdue By:</div>
                        <div class="detail-value">{{ $overdueDays }} day{{ $overdueDays === 1 ? '' : 's' }}</div>
                    </div>
                </div>

                <div class="signature">
                    <p style="margin: 0; color: #374151;">Regards,</p>
                    <p style="margin: 5px 0 0 0; color: #b91c1c; font-weight: 600;">Document Tracking System</p>
                </div>
            </div>

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
