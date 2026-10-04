<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New User Registered</title>
</head>
<body>

    <h2>New User Registered</h2>

    <p>A new user has registered on {{ config('app.name') }}.</p>

    <p><strong>Name:</strong> {{ $user->name }}</p>

    <p><strong>Email:</strong> {{ $user->email }}</p>

    <p><strong>Role:</strong> {{ $user->role }}</p>

    <p>
        Please check the admin dashboard for more details.
    </p>

</body>
</html>