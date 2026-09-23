const BASE = import.meta.env.API_URL ?? process.env.API_URL ?? 'http://app:8000';

export type Kategori = { nama: string; slug: string };

export type Berita = {
  judul: string;
  slug: string;
  tanggal: string | null;
  kategori: Kategori | null;
  totalLihat: number;
  thumbnail: string | null;
  ringkasan: string;
};

export type Dokumen = {
  id: number;
  judul: string;
  deskripsi: string;
  kategori: Kategori | null;
  totalUnduhan: number;
  ekstensi: string;
  tanggal: string | null;
  /** Byte; null bila berkasnya hilang dari penyimpanan. */
  ukuran: number | null;
  unduh: string;
};

export type Halaman<T> = { data: T[]; meta: { page: number; lastPage: number; total: number } };

/**
 * Semua data situs berasal dari Laravel. Dipanggil saat SSR lewat jaringan Docker,
 * jadi header X-Forwarded-For diteruskan supaya penghitung pengunjung melihat IP asli.
 * `cookie` diteruskan bila Laravel perlu membaca sesi pengunjung (status login admin).
 */
export async function api<T>(path: string, request?: Request, cookie?: string | null): Promise<T> {
  const teruskan: Record<string, string> = {};

  // X-Requested-With: request ini tidak dicatat sebagai "URL sebelumnya" di sesi admin.
  if (cookie) Object.assign(teruskan, { Cookie: cookie, 'X-Requested-With': 'XMLHttpRequest' });

  if (request) {
    const ip = request.headers.get('x-forwarded-for');
    if (ip) teruskan['X-Forwarded-For'] = ip;
  }

  const res = await fetch(`${BASE}/api${path}`, {
    headers: { Accept: 'application/json', ...teruskan },
  });

  if (!res.ok) throw new Error(`API ${path} gagal: ${res.status}`);

  return res.json() as Promise<T>;
}

/** Sampul pengganti untuk data tanpa gambar, juga dipakai bila gambarnya gagal dimuat. */
export const GAMBAR_DEFAULT = '/img/gambar-default.webp';
export const cadanganGambar = `this.onerror=null;this.src='${GAMBAR_DEFAULT}'`;

export function tanggalPanjang(iso: string | null | undefined): string {
  if (!iso) return '';

  return new Date(iso).toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  });
}

/** Ukuran berkas yang mudah dibaca, mis. 1,4 MB. */
export function ukuranBerkas(byte: number): string {
  const satuan = ['B', 'KB', 'MB', 'GB'];
  let i = 0;
  while (byte >= 1024 && i < satuan.length - 1) {
    byte /= 1024;
    i++;
  }
  return `${byte.toLocaleString('id-ID', { maximumFractionDigits: i ? 1 : 0 })} ${satuan[i]}`;
}

/** Isi atribut data-dokumen: rincian yang sudah diformat untuk DialogDokumen, jadi skrip peramban cukup menyalin teks. */
export function rincianDokumen(d: Dokumen): string {
  return JSON.stringify({
    judul: d.judul,
    kategori: d.kategori?.nama ?? '',
    deskripsi: d.deskripsi,
    ekstensi: d.ekstensi || '–',
    ukuran: d.ukuran == null ? '–' : ukuranBerkas(d.ukuran),
    tanggal: tanggalPanjang(d.tanggal) || '–',
    unduhan: `${d.totalUnduhan.toLocaleString('id-ID')} kali`,
    unduh: d.unduh,
  });
}

/** Query string tanpa nilai kosong, dipakai untuk tautan filter dan paginasi. */
export function query(params: Record<string, string | number | undefined | null>): string {
  const q = new URLSearchParams();

  for (const [k, v] of Object.entries(params)) {
    if (v !== undefined && v !== null && v !== '') q.set(k, String(v));
  }

  const s = q.toString();

  return s ? `?${s}` : '';
}

/**
 * Origin publik untuk URL absolut (canonical, og:image, sitemap). Di balik proxy, Astro.url bisa
 * masih http, jadi SITE_URL (diisi dari APP_URL di compose) diutamakan.
 */
export function asalSitus(url: URL): string {
  return (import.meta.env.SITE_URL || process.env.SITE_URL || url.origin).replace(/\/$/, '');
}
