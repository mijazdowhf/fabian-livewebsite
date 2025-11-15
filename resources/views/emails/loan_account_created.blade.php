<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Account Has Been Created</title>
    <style>
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .email-body {
            padding: 30px 20px;
        }
        .greeting {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }
        .success-message {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .success-message p {
            margin: 0;
            color: #155724;
        }
        .credentials-box {
            background: #f8f9fa;
            border: 2px solid #667eea;
            border-radius: 8px;
            padding: 25px;
            margin: 25px 0;
        }
        .credentials-box h2 {
            margin: 0 0 20px 0;
            color: #667eea;
            font-size: 20px;
            text-align: center;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        .credential-item {
            background: #ffffff;
            padding: 15px;
            margin: 12px 0;
            border-radius: 6px;
            border: 1px solid #dee2e6;
        }
        .credential-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .credential-value {
            font-size: 16px;
            color: #333;
            font-weight: 600;
        }
        .password-value {
            font-size: 20px;
            color: #dc3545;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .warning-box strong {
            color: #856404;
        }
        .warning-box ul {
            margin: 10px 0 0 0;
            padding-left: 20px;
            color: #856404;
        }
        .warning-box li {
            margin: 5px 0;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: 600;
            text-align: center;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
        }
        .button:hover {
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
            transform: translateY(-2px);
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
            border-top: 1px solid #dee2e6;
        }
        .footer p {
            margin: 5px 0;
        }
        .divider {
            height: 1px;
            background: #dee2e6;
            margin: 25px 0;
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 10px;
            }
            .email-body {
                padding: 20px 15px;
            }
            .credentials-box {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>🎉 Welcome to {{ config('app.name') }}!</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p class="greeting">Dear <strong>{{ $userName }}</strong>,</p>

            <div class="success-message">
                <p>✓ <strong>Your {{ $applicationType }} application has been received successfully!</strong></p>
            </div>

            <p>Thank you for submitting your application. We have created a secure account for you to track your application status and manage your information.</p>

            <!-- Credentials Box -->
            <div class="credentials-box">
                <h2>🔐 Your Login Credentials</h2>

                <div class="credential-item">
                    <div class="credential-label">Email Address</div>
                    <div class="credential-value">{{ $userEmail }}</div>
                </div>

                <div class="credential-item">
                    <div class="credential-label">Username</div>
                    <div class="credential-value">{{ $username }}</div>
                </div>

                <div class="credential-item" style="border: 2px solid #dc3545; background: #fff5f5;">
                    <div class="credential-label" style="color: #dc3545;">⚠️ Temporary Password</div>
                    <div class="password-value">{{ $password }}</div>
                </div>
            </div>

            <!-- Warning Box -->
            <div class="warning-box">
                <strong>⚠️ Important Security Notice:</strong>
                <ul>
                    <li><strong>Save these credentials</strong> in a secure place</li>
                    <li><strong>Change your password immediately</strong> after first login</li>
                    <li><strong>Never share your password</strong> with anyone</li>
                    <li><strong>Our team will never ask</strong> for your password</li>
                </ul>
            </div>

            <!-- Login Button -->
            <div class="button-container">
                <a href="{{ $loginUrl }}" class="button">
                    🔓 Login to Your Dashboard
                </a>
            </div>

            <div class="divider"></div>

            <p><strong>What's Next?</strong></p>
            <ol style="color: #6c757d;">
                <li>Click the button above to login to your dashboard</li>
                <li>Change your temporary password to a strong, unique password</li>
                <li>Complete any additional information if required</li>
                <li>Track your application status in real-time</li>
                <li>Our team will review your application and contact you soon</li>
            </ol>

            <p style="margin-top: 30px;">If you did not submit this application, please contact our support team immediately.</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>{{ config('mail.from.name') }}</strong></p>
            <p>{{ config('mail.from.address') }}</p>
            <p style="margin-top: 15px; color: #adb5bd;">
                This is an automated email. Please do not reply to this message.
            </p>
            <p style="margin-top: 10px;">
                Need help? Contact our support team
            </p>
        </div>
    </div>
</body>
</html>

