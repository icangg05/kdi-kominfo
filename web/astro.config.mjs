// @ts-check
import node from '@astrojs/node';
import tailwindcss from '@tailwindcss/vite';
import { defineConfig } from 'astro/config';

export default defineConfig({
  output: 'server',
  adapter: node({ mode: 'standalone' }),
  server: { host: true, port: 4321 },
  vite: {
    plugins: [tailwindcss()],
  },
});
