<!DOCTYPE html>
<html>
<head>
    <title>Test CSRF</title>
</head>
<body>
    <h1>Test CSRF</h1>
    <form action="{{ url('/test-csrf') }}" method="POST">
        @csrf
        <button type="submit">Enviar</button>
    </form>
</body>
</html>
