import {createRouter, createWebHashHistory} from 'vue-router';
import About from '@/core/pages/About.vue';
import ViewSupport from '@/orangehrmHelpPlugin/pages/ViewSupport.vue';
import UpdatePassword from '@/orangehrmPimPlugin/pages/updatePassword/UpdatePassword.vue';

const router = createRouter({
  history: createWebHashHistory(),
  routes: [
    {
      path: '/about',
      name: 'about',
      component: About,
    },
    {
      path: '/support',
      name: 'support',
      component: ViewSupport,
    },
    {
      path: '/update-password',
      name: 'updatePassword',
      component: UpdatePassword,
      props: {userName: 'Admin'},
    },
  ],
});

export default router;
