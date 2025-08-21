<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>CSV Contact Importer</title>
		@if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
			@vite(['resources/css/app.css', 'resources/js/app.js'])
		@endif
	</head>
	<body class="bg-[#FDFDFC] text-[#1b1b18] min-h-screen">
		<div id="app"></div>
	</body>
</html> 