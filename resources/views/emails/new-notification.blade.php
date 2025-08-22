<!DOCTYPE html>
<html>
<head>
    <title>New Notification</title>
</head>
<body>
    <h1>You have a new notification!</h1>
    <p>From: {{ $notification->user->name }}</p>
    <p>Type: {{ $notification->type }}</p>
    <p>Message: {{ $notification->data }}</p>
</body>
</html>