<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ config("app.name") }}</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background-color: #4f46e5; color: #ffffff; padding: 30px 40px; text-align: center; }
        .header h1 { margin: 0; font-size: 26px; }
        .body { padding: 30px 40px; color: #333333; line-height: 1.7; }
        .body h2 { color: #4f46e5; }
        .btn { display: inline-block; margin-top: 20px; padding: 12px 28px; background-color: #4f46e5; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; }
        .footer { background-color: #f4f4f4; text-align: center; padding: 16px; font-size: 12px; color: #888888; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Welcome to {{ config("app.name") }}!</h1>
        </div>
        <div class="body">
            <h2>Hi {{ $user->name }},</h2>
            <p>
                Thank you for creating an account with <strong>{{ config("app.name") }}</strong>.
                We are thrilled to have you on board!
            </p>
            <p>Your account details:</p>
            <ul>
                <li><strong>Name:</strong> {{ $user->name }}</li>
                <li><strong>Email:</strong> {{ $user->email }}</li>
            </ul>
            <p>Start exploring our products and enjoy a seamless shopping experience.</p>
            <a href="{{ url("/dashboard") }}" class="btn">Go to Dashboard</a>
        </div>
        <div class="footer">
            &copy; {{ date("Y") }} {{ config("app.name") }}. All rights reserved.
        </div>
    </div>
</body>
</html>
