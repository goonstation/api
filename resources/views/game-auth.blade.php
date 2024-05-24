<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="Goonhub is an information website that collects and displays statistics from the Goonstation branch of the popular game Space Station 13 developed on BYOND">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
</head>

<body>
    hello

    <script>
        function login() {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/game-auth', true);
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function() {
                if (this.readyState === XMLHttpRequest.DONE) {
                    // Request finished. Do processing here.
                    console.log('done', this.response);
                }
            };

            xhr.send('email=wirewraith@gmail.com&password=ipsum');
        }

        // window.addEventListener('message', function(event) {
        //     if (event.origin.lastIndexOf('https://127.0.0.1', 0) === 0) return;
        //     console.log('iframe page received message', event);
        //     alert(
        //         'child' + '\n' +
        //         event.source + '\n' +
        //         event.origin + ' (' + typeof event.origin + ')\n' +
        //         JSON.stringify(event.data)
        //     );
        //     event.source.postMessage('this is a test reply', '*');
        // });

        login();
    </script>
</body>

</html>
