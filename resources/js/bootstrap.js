import _ from 'lodash';
window._ = _;

// Laravel Echo
import Echo from 'laravel-echo';
import io from 'socket.io-client';

window.io = io;

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':' + (window.laravel_echo_port || 6001),
});

console.log('Echo initialized', window.Echo);

// Exemplo de listener
let i = 0;
window.Echo.channel('user-channel')
    .listen('.UserEvent', (data) => {
        i++;
        const notification = document.getElementById('notification');
        const div = document.createElement('div');
        div.className = 'alert alert-success';
        div.innerText = `${i}. ${data.title}`;
        notification.appendChild(div);
    });
