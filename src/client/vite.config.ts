import path from 'node:path';
import {defineConfig} from 'vite';
import vue from '@vitejs/plugin-vue';
import {writeFile} from 'node:fs/promises';
import {fileURLToPath, URL} from 'node:url';
import autoprefixer from 'autoprefixer'

export default defineConfig({
  base: './',
  plugins: [
    vue(),
    {
      name: 'DumpBuildTimestampPlugin',
      async closeBundle() {
        const now = Date.now().toString();
        await writeFile(path.join(__dirname, '../../web/dist/build'), now);
        console.info('Assets version: ', now);
      },
    },
  ],
  build: {
    emptyOutDir: true,
    outDir: path.resolve(__dirname, '../../web/dist'),
    rollupOptions: {
      input: path.resolve(__dirname, 'src/main.ts'),
      output: {
        inlineDynamicImports: true,
        entryFileNames: 'js/app.js',
        chunkFileNames: 'js/chunk-vendors.js',
        assetFileNames: (assetFile) => {
          const filename = assetFile.name || ''
          if (/\.css/.test(filename)) {
            return 'css/app.css';
          }
          return 'css/[name].[ext]'
        },
      },
    },
  },
  resolve: {
    alias: {
      vue: 'vue/dist/vue.esm-bundler.js',
      '@': fileURLToPath(new URL('./src', import.meta.url)),
    },
  },
  css: {
    postcss: {
      plugins: [
        autoprefixer()
      ],
    },
    preprocessorOptions: {
      scss: {
        additionalData: `@import "@/core/styles";`,
      },
    },
  },
});
