<!DOCTYPE html> 
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"> 
    <head> 
        <meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <title>Laravel</title> 
    </head> 
    <body> 

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script>
            function waitForEcho(callback) {
                if (typeof Echo !== 'undefined') {
                    callback();
                } else {
                    setTimeout(() => waitForEcho(callback), 50);
                }
            }

            console.log('ponto 1');

            waitForEcho(() => {
                console.log('ponto 2');
                Echo.channel('test2')
                    .listen('App\\Events\\TestEvent', e => {
                        console.log('Ponto 3');
                        console.log(e); // Verifica se a mensagem chegou corretamente
                    });
            });
        </script>

    </body> 
</html>
