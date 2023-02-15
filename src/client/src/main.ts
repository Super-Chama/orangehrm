import {createApp} from 'vue';
import {createPinia} from 'pinia';

import '@ohrm/oxd/fonts.css';
import '@ohrm/oxd/icons.css';
import '@ohrm/oxd/style.css';
import './core/styles/global.scss';
import './core/plugins/loader/loader.scss';
import './core/plugins/toaster/toaster.scss';

import pages from './pages';
// import router from './router';
import components from './components';

// const app = createApp(App);
const app = createApp({
  name: 'App',
  components: pages,
});

app.use(createPinia());
app.use(components);
// app.use(router);

app.mount('#app');
