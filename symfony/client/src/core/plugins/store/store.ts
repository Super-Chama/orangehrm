import {App, reactive} from 'vue';

export interface Store {
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  [key: string]: any;
}

export interface StoreAPI {
  getState: (key: string) => any;
  setState: (key: string, value: any) => void;
}

const store: Store = reactive({});

export const getState = (key: string): any => {
  return store[key];
};

export const setState = (key: string, value: any): any => {
  delete store[key];
  store[key] = value;
};

export default {
  install: (app: App) => {
    // Add Store API to Vue global scope
    const storeAPI: StoreAPI = {
      getState,
      setState,
    };

    // console.log(app._props);
    app.config.globalProperties.$store = storeAPI;
  },
};
