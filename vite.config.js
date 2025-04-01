import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from "path"
// https://vite.dev/config/
export default defineConfig({
  plugins: [vue()],
  server: {
    proxy: {
      "/api": {
        target: "http://localhost/skybank/backend/public",  // URL de tu backend
        changeOrigin: true,
        rewrite: (path) => path.replace(/^\/api/, ""),  // Reescribe correctamente la URL
      }
    }
  }  
});


