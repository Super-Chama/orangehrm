import path from 'node:path';
import {defineConfig} from 'vite';
import vue from '@vitejs/plugin-vue';
import {writeFile} from 'node:fs/promises';
import {fileURLToPath, URL} from 'node:url';

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
          return assetFile.name === 'main.css'
            ? 'css/app.css'
            : 'assets/[name].[ext]';
        },
      },
    },
  },
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url)),
    },
  },
  css: {
    preprocessorOptions: {
      scss: {
        additionalData: `@import "@/core/styles";`,
      },
    },
  },
});
