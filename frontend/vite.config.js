import vue from '@vitejs/plugin-vue'
import { defineConfig } from 'vite'
import inject from "@rollup/plugin-inject";

// https://vitejs.dev/config/
export default defineConfig({
  base: '/',
  // mode: 'production',
  plugins: [
    vue(),
    inject({   // => that should be first under plugins array
               $: 'jquery',
               jQuery: 'jquery',
      }),
    ],
  server: { 
    host: true,
  }
})
