<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>حسابداری دیاکو</title>

	<!-- Favicon -->
	<link rel="shortcut icon" href="{{asset('images/AvesTropican.png')}}">

	<!-- Theme Color -->
	<meta name="theme-color" content="#5867dd">

	<!-- Plugin styles -->
	<link rel="stylesheet" href="{{asset('AdminPanel/vendors/bundle.css')}}" type="text/css">

	<!-- App styles -->
	<link rel="stylesheet" href="{{asset('AdminPanel/assets/css/app.css')}}" type="text/css">

</head>

<body class="form-membership">

@yield('content')

	<!-- Plugin scripts -->
	<script src="{{asset('AdminPanel/vendors/bundle.js')}}"></script>

	<!-- App scripts -->
	<script src="{{asset('AdminPanel/assets/js/app.js')}}"></script>

</body>

</html>
