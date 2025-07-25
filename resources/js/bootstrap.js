import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Real-time features disabled - notifications use HTTP polling instead

// Pusher/Echo disabled - using polling for notifications instead
console.log('Real-time broadcasting disabled - using HTTP polling for notifications');

// Create a dummy Echo object that doesn't do anything
window.Echo = {
    private: () => ({
        listen: () => {}
    }),
    channel: () => ({
        listen: () => {}
    })
};
