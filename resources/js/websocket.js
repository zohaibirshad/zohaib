require('./bootstrap');

window.Vue = require('vue');

import Echo from 'laravel-echo'

window.Moment = require('moment');

window.Pusher = require('pusher-js');

import VueChatScroll from 'vue-chat-scroll'

window.VueChatScroll = VueChatScroll

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    wsHost: window.location.hostname,
    wsPort: 6001,
    wssPort: 6001,
    disableStats: true,
    encrypted: true,
    namespace: 'App.Events'
});

