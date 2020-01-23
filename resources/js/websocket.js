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
    wsHost: isProduction ? 'window.location.hostname' : 'websocket.yohli.com',
    wssPort: isProduction ? 6002 : 6001,
    wssPort: isProduction ? 6002 : 6001,
    disableStats: true,
    encrypted: true,
    namespace: 'App.Events'
});

