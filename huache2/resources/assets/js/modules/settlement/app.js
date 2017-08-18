import Vue from 'vue'

import App from './App.vue'
import router from './route/index'

const app = new Vue({
    el: '#root',
    template: `<app></app>`,
    components: { App },
    router
})