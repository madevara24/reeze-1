<html>
<head>
  <meta charset="utf-8">
  <title>Please Wait...</title>
  <body>
  </body>
  <script>
    localStorage.setItem('token', "{{$token}}")
    localStorage.setItem('user', JSON.stringify({!! $user !!}))
    window.location.href = "/"

  </script>
</head>
<body>
</body>
</html>