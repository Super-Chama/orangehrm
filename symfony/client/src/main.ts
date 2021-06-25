/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */

import {createApp} from 'vue';
import components from './components';
import pages from './pages';
import toaster, {ToasterAPI} from './core/plugins/toaster/toaster';
import loader, {LoaderAPI} from './core/plugins/loader/loader';
import store, {StoreAPI} from './core/plugins/store/store';
import './core/plugins/toaster/toaster.scss';
import './core/plugins/loader/loader.scss';
import translate from './core/plugins/i18n/translate';

const app = createApp({
  name: 'App',
  components: pages,
});

// Global Register Components
app.use(components);

app.use(toaster, {
  duration: 2500,
  persist: false,
  animation: 'oxd-toast-list',
  position: 'bottom',
});

app.use(loader);
app.use(store);

app.use(translate, {
  defaultLocale: 'en',
  translations: {
    en: {
      messages: {
        hello: 'hello',
        hello2: 'hi',
      },
    },
    fr: {
      messages: {
        hello: 'bonjour',
        hello2: 'jeme',
      },
    },
  },
});

// @ts-expect-error
const baseUrl = window.appGlobal.baseUrl;

// https://github.com/vuejs/vue-next/pull/982
declare module '@vue/runtime-core' {
  interface ComponentCustomProperties {
    $toast: ToasterAPI;
    $loader: LoaderAPI;
    $store: StoreAPI;
  }
}

app.config.globalProperties.global = {
  baseUrl,
};

app.mount('#app');
