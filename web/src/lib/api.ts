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

export type Halaman<T> = { data: T[]; meta: { page: number; lastPage: number; total: number } };

/**
 * Semua data situs berasal dari Laravel. Dipanggil saat SSR lewat jaringan Docker,
 * jadi header X-Forwarded-For diteruskan supaya penghitung pengunjung melihat IP asli.
 */
export async function api<T>(path: string, request?: Request): Promise<T> {
  const teruskan: Record<string, string> = {};

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

export function tanggalPanjang(iso: string | null | undefined): string {
  if (!iso) return '';

  return new Date(iso).toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
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
