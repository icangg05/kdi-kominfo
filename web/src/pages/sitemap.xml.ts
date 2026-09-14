import type { APIRoute } from 'astro';
import { api, asalSitus } from '../lib/api';
import { menuUtama } from '../lib/navigasi';

export const GET: APIRoute = async ({ url }) => {
  const asal = asalSitus(url);
  const statis = [...new Set(menuUtama.flatMap((m) => [m.href, ...(m.anak ?? []).map((a) => a.href)]))];

  let berita: { slug: string; diubah: string | null }[] = [];
  try {
    ({ berita } = await api<{ berita: typeof berita }>('/sitemap'));
  } catch {
    // Tetap kirim halaman statis walau API sedang tidak bisa dihubungi.
  }

  const entri = [
    ...statis.map((p) => `<url><loc>${asal}${p}</loc></url>`),
    ...berita.map(
      (b) => `<url><loc>${asal}/berita/${encodeURIComponent(b.slug)}</loc>${b.diubah ? `<lastmod>${b.diubah}</lastmod>` : ''}</url>`,
    ),
  ];

  return new Response(
    `<?xml version="1.0" encoding="UTF-8"?>\n<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">${entri.join('')}</urlset>`,
    { headers: { 'Content-Type': 'application/xml; charset=utf-8' } },
  );
};
