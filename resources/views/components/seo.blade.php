@props(['title' => 'Jelajah Jepara - Portal Wisata', 'description' => 'Temukan keindahan Jepara, dari wisata alam memukau hingga kekayaan budaya yang autentik. Panduan lengkap liburanmu ada di sini!', 'image' => asset('images/logo-kura.png'), 'type' => 'website'])

<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $image }}">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ url()->current() }}">
<meta property="twitter:title" content="{{ $title }}">
<meta property="twitter:description" content="{{ $description }}">
<meta property="twitter:image" content="{{ $image }}">
