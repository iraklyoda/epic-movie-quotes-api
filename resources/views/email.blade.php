@props([
    'url',
    'user',
])

    <!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Epic Movie Quote</title>
</head>

<body>
<header>
    <img src="{{asset('images/mail_header.svg')}}" alt="mail">
    <h2>MOVIE QUOTES</h2>
</header>
<main>
    <p>Hola {{$user}}</p>
    <p>Thanks for joining Movie quotes! We really appreciate it. Please click the button below to verify your account:</p>
    <button><a href="{{$url}}">Verify account</a></button>
    <p>If clicking doesn't work, you can try copying and pasting it to your browser:</p>
    <p class="max-width">{{$url}}</p>
    <p>If you have any problems, please contact us: support@moviequotes.ge</p>
    <p>MovieQuotes Crew</p>
</main>
</body>
</html>

<style>
    @font-face {
        font-family: "Helvetica Neue";
        src: url('{{ asset('fonts/HelveticaNeue.tff') }}');
    }

    *{
        color: white;
    }
    a:link { text-decoration: none; }
    a:visited { text-decoration: none; }
    a:hover { text-decoration: none; }
    a:active { text-decoration: none; }
    button, input[type="submit"], input[type="reset"] {
        background: none;
        color: inherit;
        border: none;
        padding: 0;
        font: inherit;
        cursor: pointer;
        outline: inherit;
    }
    body {
        background-color: #0D0B14;
        font-family: 'Helvetica Neue', sans-serif;
    }
    body p {
        margin: 24px 0;
    }
    header{
        width: 100px;
        margin: 78px auto 0 auto;
    }
    header img {
        position: relative;
        left: 50%;
        transform: translateX(-50%);
    }
    header h2 {
        font-size: 12px;
        position: relative;
        left: 50%;
        transform: translateX(-50%);
    }
    main {
        margin-left: 10%;
    }
    main button{
        width: 128px;
        height: 38px;
        border-radius: 4px;
        background-color: #E31221;
    }
    .max-width {
        max-width: 1229px;
        word-wrap: break-word;
    }

    @media only screen and (max-width: 600px) {
        main {
            margin-left: 20px;
        }

    }


</style>
