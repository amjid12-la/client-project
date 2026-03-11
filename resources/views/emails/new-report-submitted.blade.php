<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Report Submitted</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .email-body {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }
        .intro-text {
            margin-bottom: 25px;
            color: #555;
        }
        .report-details {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .report-details h3 {
            margin-top: 0;
            margin-bottom: 15px;
            color: #667eea;
            font-size: 16px;
        }
        .detail-row {
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e0e0e0;
        }
        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .detail-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 4px;
        }
        .detail-value {
            color: #555;
            margin: 0;
        }
        .action-button {
            text-align: center;
            margin: 30px 0;
        }
        .action-button a {
            display: inline-block;
            padding: 14px 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            transition: transform 0.2s;
        }
        .action-button a:hover {
            transform: translateY(-2px);
        }
        .closing-text {
            margin-top: 25px;
            color: #555;
        }
        .email-footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #777;
            font-size: 14px;
            border-top: 1px solid #e0e0e0;
        }
        .signature {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>🔔 New Report Submitted</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <div class="greeting">Hello Admin,</div>
            
            <div class="intro-text">
                A new report has been submitted to the Report Management System and requires your immediate attention.
            </div>

            <!-- Report Details -->
            <div class="report-details">
                <h3>📋 Report Details</h3>
                
                <div class="detail-row">
                    <div class="detail-label">👤 Submitted By:</div>
                    <div class="detail-value">{{ $report->individual_name ?? 'Not provided' }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">📍 Location:</div>
                    <div class="detail-value">{{ $report->location ?? 'Not provided' }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">📅 Submitted On:</div>
                    <div class="detail-value">{{ $report->created_at->format('F d, Y \a\t h:i A') }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">📝 Description:</div>
                    <div class="detail-value">{{ Str::limit($report->narrative ?? 'No description', 150) }}</div>
                </div>
            </div>

            <!-- Action Button -->
            <div class="action-button">
                <a href="{{ $reportUrl }}">Review Report Now</a>
            </div>

            <!-- Closing Text -->
            <div class="closing-text">
                Please review this report and take appropriate action (Approve/Reject).
            </div>

            <div class="closing-text">
                Thank you for maintaining the quality of our reporting system.
            </div>

            <!-- Signature -->
            <div class="signature">
                <strong>Best regards,</strong><br>
                Report Management System
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>This is an automated notification from the Report Management System.</p>
            <p>© {{ date('Y') }} Report Management System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
