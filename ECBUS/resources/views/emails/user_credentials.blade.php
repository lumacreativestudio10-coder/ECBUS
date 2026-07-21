<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your ECBUS Account Credentials</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #800000; /* Primary Maroon */
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }
        .content {
            padding: 30px;
            color: #333333;
            line-height: 1.6;
        }
        .credentials-box {
            background-color: #f9f9f9;
            border-left: 4px solid #800000;
            padding: 15px;
            margin: 20px 0;
            font-family: monospace;
            font-size: 16px;
        }
        .btn-container {
            text-align: center;
            margin: 30px 0;
        }
        .btn {
            background-color: #800000;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
        }
        .footer {
            background-color: #eeeeee;
            color: #777777;
            text-align: center;
            padding: 15px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h2>Welcome to ECBUS!</h2>
        </div>
        <div class="content">
            <p>Hello <strong>{{ $user->name }}</strong>,</p>
            <p>Your account has been successfully created on the ECBUS portal. Below are your login credentials. Please keep them safe.</p>
            
            <div class="credentials-box">
                <strong>Email:</strong> {{ $user->email }}<br>
                <strong>Password:</strong> {{ $password }}
            </div>
            
            <p>We highly recommend changing your password after your first login for security purposes.</p>
            
            <div class="btn-container">
                <a href="{{ url('/') }}" class="btn">Login to Portal</a>
            </div>
            
            <p>If you have any questions, please contact our support team.</p>
            <p>Best regards,<br>The ECBUS Team</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} ECBUS. All rights reserved.
        </div>
    </div>
</body>
</html>
