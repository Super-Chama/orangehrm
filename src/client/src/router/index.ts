import About from '@/core/pages/About.vue';
import pimRoutes from '@/orangehrmPimPlugin';
import {createRouter, createWebHashHistory} from 'vue-router';
import {createRouteGroup} from '@/core/util/helper/route-group';
import ViewSupport from '@/orangehrmHelpPlugin/pages/ViewSupport.vue';

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
    ...createRouteGroup('pim', pimRoutes),
  ],
});

export default router;
