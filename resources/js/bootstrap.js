import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Importar Socket.IO client globalmente
import io from 'socket.io-client';
window.io = io;