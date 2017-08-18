import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import Index from '../views/Index.vue'
import Detail from '../views/Detail.vue'
import Notfound from '../views/public/NotFound.vue'
import MailFile from '../views/MailFile.vue'
import MailHistory from '../views/MailHistory.vue'

const router = new VueRouter({
    mode: 'history', //去掉url中的#
    routes: [
        { path: '/dealer/prices/settlement/', component: Index },
        { path: '/dealer/prices/settlement/detail/:id', component: Detail },
        { path: '/dealer/prices/settlement/mail_file', component: MailFile },
        { path: '/dealer/prices/settlement/mail_history', component: MailHistory },
        { path: '/dealer/prices/settlement/404', component: Notfound },
    ]
})

export default router
