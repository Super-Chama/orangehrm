import {createApp} from 'vue';
import {createPinia} from 'pinia';
import createI18n from './core/plugins/i18n/translate';

import '@ohrm/oxd/fonts.css';
import '@ohrm/oxd/icons.css';
import '@ohrm/oxd/style.css';
import './core/styles/global.scss';
import './core/plugins/loader/loader.scss';
import './core/plugins/toaster/toaster.scss';

import pages from './pages';
import router from './router';
import components from './components';

// const app = createApp(App);
const app = createApp({
  name: 'App',
  components: pages,
});

// @ts-expect-error: appGlobal is not in window object by default
const baseUrl = window.appGlobal.baseUrl;

const {i18n, init} = createI18n({
  baseUrl: baseUrl,
  resourceUrl: 'core/i18n/messages',
});
app.use(i18n);

app.use(createPinia());
app.use(components);
app.use(router);

app.config.globalProperties.global = {
  baseUrl,
};

init().then(() => app.mount('#app'));
