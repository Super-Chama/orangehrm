import {App, ComponentOptions, getCurrentInstance} from 'vue';

export interface Messages {
  [name: string]: string;
}

export interface Languages {
  [locale: string]: {
    messages: Messages;
  };
}

export interface LanguageOptions {
  defaultLocale: string;
  translations: Languages;
}

export default {
  install: (app: App, options: LanguageOptions) => {
    const defaultLocale = options.defaultLocale;
    const translations = options.translations;

    const translateIt = (
      key: string,
      locale?: string,
      fallback = '',
    ): string => {
      const loc = locale ? locale : defaultLocale;
      if (translations[loc] && translations[loc].messages[key]) {
        return translations[loc].messages[key];
      } else {
        return fallback;
      }
    };

    app.mixin({
      beforeCreate(): void {
        this.$t = translateIt;
      },
    });
  },
};
