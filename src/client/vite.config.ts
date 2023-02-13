import path from 'node:path';
import {defineConfig} from 'vite';
import vue from '@vitejs/plugin-vue';
import {fileURLToPath, URL} from 'node:url';

export default defineConfig({
  plugins: [vue()],
  build: {
    manifest: false,
    emptyOutDir: true,
    outDir: path.resolve(__dirname, '../../web/dist'),
    rollupOptions: {
      input: path.resolve(__dirname, 'src/main.ts'),
    },
  },
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url)),
    },
  },
});
