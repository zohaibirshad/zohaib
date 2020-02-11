require('./bootstrap');

window.Vue = require('vue');

import Echo from 'laravel-echo'

window.Moment = require('moment');

window.Pusher = require('pusher-js');

import VueChatScroll from 'vue-chat-scroll'

window.VueChatScroll = VueChatScroll;

let isProduction = process.env.MIX_WS_CONNECT_PRODUCTION === 'true';

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    wsHost: isProduction ? 'window.location.hostname' : 'websocket.yohli.com',
    wsPort: isProduction ? 6001 : 6001,
    wssHost: isProduction ? 'window.location.hostname' : 'websocket.yohli.com',
    wssPort: isProduction ? 6001 : 6001,
    disableStats: true,
    encrypted: true,
    namespace: 'App.Events'
});

