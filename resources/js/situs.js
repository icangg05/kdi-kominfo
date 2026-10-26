/*
 * Perilaku peramban situs publik. Livewire menyuntikkan skripnya sendiri (beserta Alpine);
 * berkas ini hanya untuk hal yang tidak butuh server: tema, jam, menu, dialog, aksesibilitas.
 *
 * Situs berpindah halaman lewat wire:navigate: <body> ditukar tanpa memuat ulang dokumen dan modul ini
 * hanya berjalan sekali. Karena itu ada dua jenis pemasangan:
 * - sekali per dokumen: delegasi event di `document` dan jam (elemen dicari ulang saat dipakai);
 * - per halaman (pasangHalaman): elemen di <body> yang baru, dijalankan lagi setiap `livewire:navigated`.
 * Timer dan observer milik halaman lama dibereskan di `livewire:navigating` (bersihkanHalaman).
 */

const akar = document.documentElement;
const kurangiGerak = matchMedia('(prefers-reduced-motion: reduce)').matches;
// "Stop Animasi" di panel aksesibilitas bisa dinyalakan kapan saja, jadi diperiksa setiap kali dipakai.
const diam = () => kurangiGerak || akar.hasAttribute('data-henti-animasi');

/** Dibereskan sebelum halaman ditukar: interval giliran sorotan, IntersectionObserver, dll. */
let pembersih = [];
const bersihkanNanti = (fn) => pembersih.push(fn);

/* ==========================================================================
 * Sekali per dokumen
 * ======================================================================== */

/* --- Jam WITA di bilah informasi -------------------------------------- */

const formatWita = new Intl.DateTimeFormat('id-ID', {
  timeZone: 'Asia/Makassar',
  hour: '2-digit',
  minute: '2-digit',
  second: '2-digit',
  hourCycle: 'h23',
});

/** Jam dan sapaan menurut WITA (Asia/Makassar), apa pun zona waktu perangkat pengunjung; sama dengan App\Support\WaktuWita. */
const waktuWita = (tanggal = new Date()) => {
  const bagian = formatWita.formatToParts(tanggal);
  const ambil = (t) => bagian.find((p) => p.type === t)?.value ?? '00';
  const j = Number(ambil('hour'));

  return {
    jam: `${ambil('hour')}.${ambil('minute')}.${ambil('second')}`,
    sapaan: j < 4 ? 'Selamat malam' : j < 11 ? 'Selamat pagi' : j < 15 ? 'Selamat siang' : j < 18 ? 'Selamat sore' : 'Selamat malam',
  };
};

// Satu interval untuk seluruh sesi; elemennya dicari ulang tiap detik karena header ikut ditukar.
const tik = () => {
  const w = waktuWita();
  const jam = document.querySelector('[data-jam]');
  const sapaan = document.querySelector('[data-sapaan]');
  if (jam) jam.textContent = w.jam;
  if (sapaan) sapaan.textContent = w.sapaan;
};

setTimeout(() => {
  tik();
  setInterval(tik, 1000);
}, 1000 - (Date.now() % 1000));

/* --- Tema terang/gelap ------------------------------------------------ */

// Bawaan terang; disimpan di kunci `theme` milik Filament supaya admin ikut.
const gantiTema = () => {
  const gelap = akar.dataset.tema !== 'gelap';
  if (gelap) akar.dataset.tema = 'gelap';
  else delete akar.dataset.tema;
  try {
    localStorage.setItem('theme', gelap ? 'dark' : 'light');
  } catch {}
};

// Ada dua tombol: di bilah informasi dan di nav yang melekat.
document.addEventListener('click', (e) => {
  const tombolTema = e.target.closest('[data-tombol-tema]');
  if (!tombolTema) return;

  // Tema baru menyebar melingkar dari tombol yang diklik; tanpa View Transition API atau saat gerak dikurangi, langsung berganti.
  if (!document.startViewTransition || diam()) return gantiTema();
  // Klik lewat keyboard saat animasi berjalan diabaikan; mouse dan sentuh sudah diblok CSS data-ganti-tema.
  if ('gantiTema' in akar.dataset) return;

  const r = tombolTema.getBoundingClientRect();
  const x = r.left + r.width / 2;
  const y = r.top + r.height / 2;
  const jari = Math.hypot(Math.max(x, innerWidth - x), Math.max(y, innerHeight - y));

  akar.dataset.gantiTema = '';
  const transisi = document.startViewTransition(gantiTema);
  transisi.finished.finally(() => delete akar.dataset.gantiTema);
  transisi.ready.then(() => {
    akar.animate(
      { clipPath: [`circle(0 at ${x}px ${y}px)`, `circle(${jari}px at ${x}px ${y}px)`] },
      { duration: 550, easing: 'cubic-bezier(0.4, 0, 0.2, 1)', pseudoElement: '::view-transition-new(root)' },
    );
  });
});

/* --- Menu seluler ------------------------------------------------------ */

const aturMenu = (buka) => {
  const menu = document.getElementById('menu-seluler');
  const tombol = document.querySelector('[data-tombol-menu]');
  if (!menu) return;
  // Halaman dikunci selama menu terbuka, jadi menu harus muat dari posisinya sekarang sampai dasar layar.
  if (buka) menu.style.maxHeight = `${innerHeight - menu.getBoundingClientRect().top}px`;
  tombol?.setAttribute('aria-expanded', String(buka));
  tombol?.setAttribute('aria-label', buka ? 'Tutup menu' : 'Buka menu');
  menu.toggleAttribute('data-terbuka', buka);
};

document.addEventListener('click', (e) => {
  const tombol = e.target.closest('[data-tombol-menu]');
  if (tombol) aturMenu(tombol.getAttribute('aria-expanded') !== 'true');
});

document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && document.getElementById('menu-seluler')?.hasAttribute('data-terbuka')) {
    aturMenu(false);
    document.querySelector('[data-tombol-menu]')?.focus();
  }
});

/* --- Pencarian GET (header, beranda) lewat wire:navigate --------------- */

// Formulir [data-cari] tetap form GET biasa tanpa JavaScript; dengan Livewire, hasilnya dibuka tanpa memuat ulang.
document.addEventListener('submit', (e) => {
  const form = e.target.closest('form[data-cari]');
  if (!form || !window.Livewire?.navigate) return;
  e.preventDefault();
  const query = new URLSearchParams(new FormData(form)).toString();
  window.Livewire.navigate(`${form.getAttribute('action')}${query ? `?${query}` : ''}`);
});

/* --- Dialog gambar: semua gambar di dalam [data-perbesar] -------------- */

let gambarDibuka = null;

const bukaGambar = (img) => {
  const dialog = document.getElementById('dialog-gambar');
  if (!dialog) return;
  const besar = dialog.querySelector('img');
  const keterangan = dialog.querySelector('figcaption');

  gambarDibuka = img;
  // Salinan di halaman sudah ada di cache, jadi langsung menggantikan gambar sebelumnya tanpa kedip.
  besar.removeAttribute('srcset');
  besar.src = img.currentSrc || img.src;
  besar.alt = img.alt;
  // Keterangan butuh ruang di bawah gambar, jadi tinggi gambar dikurangi saat keterangan tampil.
  keterangan.hidden = !img.dataset.judul;
  keterangan.querySelector('[data-judul]').textContent = img.dataset.judul ?? '';
  keterangan.querySelector('[data-tanggal]').textContent = img.dataset.tanggal ?? '';
  besar.style.maxHeight = keterangan.hidden ? '' : 'calc(100dvh - 7rem)';
  dialog.showModal();

  // Versi terbesar yang pas di layar dimuat di belakang, baru dipasang setelah siap.
  if (!img.srcset) return;
  const muat = new Image();
  muat.sizes = besar.sizes = '100vw';
  muat.srcset = img.srcset;
  muat.onload = () => {
    if (gambarDibuka === img) besar.srcset = img.srcset;
  };
};

// Gambar yang dibungkus tautan tetap membuka tautannya; yang dibungkus tombol memakai tombol itu sebagai pemicu.
const gambarDari = (target) => {
  const tombol = target.closest('[data-perbesar] button');
  const img = tombol ? tombol.querySelector('img:not([aria-hidden])') : target.closest('[data-perbesar] img:not([aria-hidden])');
  return img && !img.closest('a') ? img : null;
};

document.addEventListener('click', (e) => {
  const img = gambarDari(e.target);
  if (img) bukaGambar(img);
});

document.addEventListener('keydown', (e) => {
  if (e.key !== 'Enter' && e.key !== ' ') return;
  if (!e.target.matches?.('[data-perbesar] img[tabindex]')) return;
  e.preventDefault();
  bukaGambar(e.target);
});

/* --- Dialog rincian dokumen: isi disalin dari JSON di atribut data-dokumen */

document.addEventListener('click', (e) => {
  const tombol = e.target.closest('[data-dokumen]');
  const dialog = document.getElementById('dialog-dokumen');
  if (!tombol || !dialog) return;

  const d = JSON.parse(tombol.dataset.dokumen);
  dialog.querySelectorAll('[data-isi]').forEach((el) => (el.textContent = d[el.dataset.isi] ?? ''));
  // Berkas tidak ada (unduh null): tautan tanpa href dan dinonaktifkan.
  const unduh = dialog.querySelector('[data-unduh]');
  if (d.unduh) unduh.href = d.unduh;
  else unduh.removeAttribute('href');
  unduh.setAttribute('aria-disabled', String(!d.unduh));
  dialog.showModal();
});

/* --- Dialog video: pemutar YouTube baru dimuat saat diklik ------------- */

// Tanpa JavaScript tautan membuka YouTube; dengan JavaScript video diputar di dialog.
document.addEventListener('click', (e) => {
  const tautan = e.target.closest('a[data-video]');
  const dialog = document.getElementById('dialog-video');
  if (!tautan || !dialog) return;

  e.preventDefault();
  const iframe = document.createElement('iframe');
  iframe.src = `https://www.youtube-nocookie.com/embed/${tautan.dataset.video}?autoplay=1&rel=0`;
  iframe.title = tautan.dataset.judul ?? 'Video';
  iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
  iframe.allowFullscreen = true;
  iframe.className = 'size-full';
  dialog.querySelector('[data-pemutar]').replaceChildren(iframe);
  dialog.setAttribute('aria-label', iframe.title);
  dialog.querySelector('[data-judul]').textContent = iframe.title;
  dialog.querySelector('[data-tanggal]').textContent = tautan.dataset.tanggal ?? '';
  dialog.showModal();
});

// Klik di latar gelap (di luar isi) menutup dialog. Dialog gambar dan video tak berlatar isi, jadi apa pun selain gambar/keterangan/pemutar.
document.addEventListener('click', (e) => {
  const dialog = e.target.closest('dialog.dialog-halus');
  if (!dialog?.open) return;
  const luar = dialog.id === 'dialog-gambar' ? !e.target.closest('img, figcaption') : e.target === dialog;
  if (luar) dialog.close();
});

// Iframe dibuang saat dialog video ditutup supaya suaranya ikut berhenti. `close` tidak menggelembung, jadi pakai fase capture.
document.addEventListener('close', (e) => e.target.id === 'dialog-video' && e.target.querySelector('[data-pemutar]')?.replaceChildren(), true);

/* --- Rincian berita: salin tautan -------------------------------------- */

document.addEventListener('click', async (e) => {
  const tombol = e.target.closest('[data-salin]');
  if (!tombol) return;
  const label = tombol.querySelector('span');
  try {
    await navigator.clipboard.writeText(tombol.dataset.salin);
    label.textContent = 'Tautan disalin';
  } catch {
    label.textContent = 'Gagal menyalin';
  }
  setTimeout(() => (label.textContent = 'Salin tautan'), 2000);
});

/* --- Panel aksesibilitas: status dan pembaca suara --------------------- */

// Pilihan disimpan di kunci `aksesibilitas`; window.terapkanPreferensi (skrip di <head> layout) memasangnya sebelum dilukis.
const suara = 'speechSynthesis' in window ? speechSynthesis : null;
let simpan = {};
// Ucapan terakhir dari bacaan terbaru; hanya ucapan ini yang menutup tanda bicara.
let ucapanTerakhir = null;
// Tautan, tombol, dan gambar yang bisa diperbesar: klik pertama hanya membacanya, klik kedua pada kontrol yang sama baru menjalankannya.
let kontrolDibaca = null;

const tandaiBicara = (aktif) => {
  const panel = document.getElementById('aksesibilitas');
  panel?.toggleAttribute('data-bicara', aktif);
  const berhenti = panel?.querySelector('[data-berhenti]');
  if (berhenti) berhenti.disabled = !aktif;
};

const hentikanSuara = () => {
  suara?.cancel();
  tandaiBicara(false);
};

const baca = (teks) => {
  if (!suara) return;
  suara.cancel();
  const logat = suara.getVoices().find((v) => /^id[-_]/i.test(v.lang)) ?? null;
  // Chrome memutus ucapan panjang di tengah jalan, jadi teks dipecah per kalimat lalu diantrekan.
  const kalimat = teks.split(/(?<=[.!?])\s+|\n+/).filter((k) => k.trim());
  for (const k of kalimat) {
    const ucapan = new SpeechSynthesisUtterance(k);
    ucapan.lang = 'id-ID';
    ucapan.voice = logat;
    ucapan.onend = ucapan.onerror = () => ucapan === ucapanTerakhir && tandaiBicara(false);
    suara.speak(ucapan);
    ucapanTerakhir = ucapan;
  }
  tandaiBicara(kalimat.length > 0);
};

addEventListener('pagehide', hentikanSuara);

// Selektor ini sama dengan sorotan hover `data-baca-klik` di situs.css.
// Fase capture supaya penahan kontrol jalan sebelum handler klik lain (menu, dialog, wire:navigate, Livewire).
document.addEventListener(
  'click',
  (e) => {
    const panel = document.getElementById('aksesibilitas');
    if (!simpan['baca-klik'] || panel?.contains(e.target)) return;
    const target = e.target;
    const el = target.closest('p, h1, h2, h3, h4, h5, h6, li, dt, dd, td, th, figcaption, blockquote, label, a, button');
    const kontrol = target.closest('a[href], button, [data-perbesar] img');
    const teks = el?.innerText.trim() || el?.getAttribute('aria-label') || kontrol?.getAttribute('alt');
    if (!teks) return;
    if (kontrol && kontrol === kontrolDibaca) return;
    kontrolDibaca = kontrol;
    if (kontrol) {
      e.preventDefault();
      e.stopPropagation();
    }
    baca(teks);
  },
  true,
);

/* --- Siklus wire:navigate ---------------------------------------------- */

document.addEventListener('livewire:navigating', (e) => {
  // Livewire memotret halaman ini untuk tombol Back sesudah event ini, jadi rapikan dulu supaya potretnya bersih.
  aturMenu(false);
  document.querySelectorAll('dialog[open]').forEach((d) => d.close());
  document.getElementById('aksesibilitas')?.hidePopover?.();
  hentikanSuara();
  kontrolDibaca = null;
  pembersih.forEach((fn) => fn());
  pembersih = [];

  // Atribut <html> ikut diganti dengan milik halaman tujuan; pasang lagi tema dan aksesibilitas sebelum dilukis.
  e.detail?.onSwap?.(() => window.terapkanPreferensi?.());
});

/* ==========================================================================
 * Per halaman
 * ======================================================================== */

let bodyTerpasang = null;

const pasangHalaman = () => {
  // livewire:navigated juga terpicu saat halaman pertama dimuat; jangan pasang dua kali di <body> yang sama.
  if (bodyTerpasang === document.body) return;
  bodyTerpasang = document.body;

  tik();
  pasangHeader();
  pasangKeAtas();
  pasangGambarIsi();
  pasangSorotan();
  pasangGeser();
  pasangKategori();
  pasangAksesibilitas();
};

/* --- Header: nav melekat, dialog survei -------------------------------- */

const pasangHeader = () => {
  // Nav desktop melekat tepat saat bilah identitas sudah lewat dari layar.
  const kepala = document.querySelector('[data-kepala]');
  const nav = document.querySelector('[data-nav]');

  if (kepala && nav) {
    const pengamat = new IntersectionObserver(([e]) => nav.toggleAttribute('data-melekat', !e.isIntersecting && e.boundingClientRect.top < 0));
    pengamat.observe(kepala);
    bersihkanNanti(() => pengamat.disconnect());
  }

  // Survei dari situs luar baru dimuat saat dibuka, bukan pada setiap kunjungan halaman.
  const dialogSurvei = document.getElementById('dialog-survei');

  if (dialogSurvei) {
    const bingkai = dialogSurvei.querySelector('iframe');

    document.querySelectorAll('[data-buka-survei]').forEach((b) =>
      b.addEventListener('click', () => {
        aturMenu(false);
        if (!bingkai.src) bingkai.src = bingkai.dataset.src;
        dialogSurvei.showModal();
      }),
    );
    // Klik di latar gelap ditangani delegasi dialog di atas: isi dialog memenuhi kotaknya, jadi klik pada dialog itu sendiri pasti di latar.
  }
};

/* --- Footer: tombol kembali ke atas ------------------------------------ */

const pasangKeAtas = () => {
  const tombol = document.querySelector('[data-ke-atas]');
  const penanda = document.querySelector('[data-penanda-atas]');
  if (!tombol || !penanda) return;

  // Muncul setelah penanda 400px keluar dari layar.
  const pengamat = new IntersectionObserver(([e]) => {
    tombol.hidden = e.isIntersecting || e.boundingClientRect.top > 0;
  });
  pengamat.observe(penanda);
  bersihkanNanti(() => pengamat.disconnect());

  tombol.addEventListener('click', () => scrollTo({ top: 0, behavior: diam() ? 'auto' : 'smooth' }));
};

/* --- Gambar di isi berita (dari editor) bisa diperbesar ---------------- */

// Gambar di daftar Livewire sudah diberi tabindex="0" dan cursor-zoom-in di Blade, supaya ikut terbawa saat
// dirender ulang; sisanya (mis. gambar di isi berita) diberi di sini.
const pasangGambarIsi = () => {
  document.querySelectorAll('[data-perbesar] img:not([aria-hidden]):not([tabindex])').forEach((img) => {
    if (img.closest('a, button')) return;
    img.tabIndex = 0;
    img.classList.add('cursor-zoom-in');
  });
};

/* --- Beranda: sorotan berita ------------------------------------------- */

// Tombol nomor memilih slide; bergilir tiap 7 detik (durasi garis sorot-hitung di situs.css ikut angka ini),
// tetap jalan saat kursor di atasnya, kecuali fokus keyboard atau gerak dikurangi.
const pasangSorotan = () => {
  const sorotan = document.querySelector('[data-sorotan]');
  if (!sorotan) return;

  const slide = [...sorotan.querySelectorAll('[data-slide]')];
  const tombol = [...sorotan.querySelectorAll('[data-ke]')];
  const latar = [...sorotan.querySelectorAll('[data-latar]')];
  let aktif = 0;
  let giliran;

  const tampil = (n) => {
    aktif = (n + slide.length) % slide.length;
    slide.forEach((s, i) => (s.hidden = i !== aktif));
    latar.forEach((l, i) => l.classList.toggle('opacity-0', i !== aktif));
    tombol.forEach((t, i) => t.setAttribute('aria-current', String(i === aktif)));
  };

  const jalan = () => {
    clearInterval(giliran);
    if (!kurangiGerak && slide.length > 1) giliran = setInterval(() => diam() || tampil(aktif + 1), 7000);
  };

  // Halaman yang dipulihkan tombol Back bisa membawa slide terakhir; mulai lagi dari yang pertama.
  tampil(0);
  tombol.forEach((t, i) => t.addEventListener('click', () => { tampil(i); jalan(); }));
  sorotan.addEventListener('focusin', () => clearInterval(giliran));
  sorotan.addEventListener('focusout', jalan);
  jalan();
  bersihkanNanti(() => clearInterval(giliran));
};

/* --- Beranda: carousel kabar ------------------------------------------- */

// Tombol panah menggeser sejauh satu layar jalur.
const pasangGeser = () => {
  document.querySelectorAll('[data-geser]').forEach((wadah) => {
    const jalur = wadah.querySelector('[data-jalur]');
    wadah.querySelectorAll('[data-arah]').forEach((b) =>
      b.addEventListener('click', () =>
        jalur?.scrollBy({ left: Number(b.dataset.arah) * jalur.clientWidth * 0.9, behavior: diam() ? 'auto' : 'smooth' }),
      ),
    );
  });
};

/* --- Dokumen: baris kategori ------------------------------------------- */

// Kategori aktif bisa berada di luar layar setelah halaman dibuka; geser barisnya (bukan halaman) ke tengah kategori itu.
const pasangKategori = () => {
  const baris = document.querySelector('[data-kategori]');
  const aktif = baris?.querySelector('[aria-current]');
  if (baris && aktif) baris.scrollLeft = aktif.offsetLeft - (baris.clientWidth - aktif.offsetWidth) / 2;
};

/* --- Panel aksesibilitas: tombol-tombol di panel ----------------------- */

const pasangAksesibilitas = () => {
  const panel = document.getElementById('aksesibilitas');
  if (!panel) return;

  const pemicu = document.querySelector('[data-pemicu-aksesibilitas]');
  const lencana = pemicu.querySelector('[data-jumlah]');
  const labelSkala = panel.querySelector('[data-skala]');
  const [kecilkan, besarkan] = panel.querySelectorAll('[data-ubah-skala]');
  const tombolOpsi = [...panel.querySelectorAll('[data-opsi]')];
  const tombolReset = panel.querySelector('[data-reset]');

  const segarkan = () => {
    const skala = Number(simpan.skala) || 100;
    akar.style.fontSize = skala === 100 ? '' : `${skala}%`;
    labelSkala.textContent = `${skala}%`;
    kecilkan.disabled = skala <= 80;
    besarkan.disabled = skala >= 150;

    let jumlah = skala === 100 ? 0 : 1;
    tombolOpsi.forEach((b) => {
      const aktif = Boolean(simpan[b.dataset.opsi]);
      akar.toggleAttribute(`data-${b.dataset.opsi}`, aktif);
      b.setAttribute('aria-pressed', String(aktif));
      jumlah += Number(aktif);
    });

    lencana.hidden = jumlah === 0;
    lencana.textContent = String(jumlah);
    pemicu.setAttribute('aria-label', jumlah ? `Mode aksesibilitas, ${jumlah} pengaturan aktif` : 'Mode aksesibilitas');
    tombolReset.disabled = jumlah === 0;
  };

  const ubah = (baru) => {
    simpan = baru;
    try {
      localStorage.setItem('aksesibilitas', JSON.stringify(simpan));
    } catch {}
    segarkan();
  };

  const muat = () => {
    try {
      simpan = JSON.parse(localStorage.getItem('aksesibilitas') ?? '{}');
    } catch {
      simpan = {};
    }
    segarkan();
  };

  muat();
  // Kembali lewat tombol Back dari halaman non-SPA memulihkan halaman dari cache; pilihan bisa sudah diganti di sana.
  const pulih = (e) => e.persisted && muat();
  addEventListener('pageshow', pulih);
  bersihkanNanti(() => removeEventListener('pageshow', pulih));

  [kecilkan, besarkan].forEach((b) =>
    b.addEventListener('click', () => {
      const skala = (Number(simpan.skala) || 100) + Number(b.dataset.ubahSkala);
      ubah({ ...simpan, skala: Math.min(150, Math.max(80, skala)) });
    }),
  );

  tombolOpsi.forEach((b) => b.addEventListener('click', () => ubah({ ...simpan, [b.dataset.opsi]: !simpan[b.dataset.opsi] })));

  // Pembaca suara bawaan peramban; tanpa dukungan, kontrol suara disembunyikan.
  if (!suara) panel.querySelectorAll('[data-suara]').forEach((el) => (el.hidden = true));

  tombolReset.addEventListener('click', () => {
    hentikanSuara();
    ubah({});
  });

  panel.querySelector('[data-baca-halaman]').addEventListener('click', () => baca(document.getElementById('konten').innerText));
  panel.querySelector('[data-berhenti]').addEventListener('click', hentikanSuara);
};

/* --- Mulai ------------------------------------------------------------- */

document.addEventListener('livewire:navigated', pasangHalaman);
// Halaman tanpa komponen Livewire (mis. 404) tidak memuat Livewire, jadi livewire:navigated tidak pernah datang.
if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', pasangHalaman);
else pasangHalaman();
