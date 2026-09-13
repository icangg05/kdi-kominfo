const format = new Intl.DateTimeFormat('id-ID', {
  timeZone: 'Asia/Makassar',
  hour: '2-digit',
  minute: '2-digit',
  second: '2-digit',
  hourCycle: 'h23',
});

/** Jam dan sapaan menurut WITA (Asia/Makassar), apa pun zona waktu server atau perangkat pengunjung. */
export function waktuWita(tanggal = new Date()) {
  const bagian = format.formatToParts(tanggal);
  const ambil = (t: string) => bagian.find((p) => p.type === t)?.value ?? '00';
  const j = Number(ambil('hour'));

  return {
    jam: `${ambil('hour')}.${ambil('minute')}.${ambil('second')}`,
    sapaan: j < 4 ? 'Selamat malam' : j < 11 ? 'Selamat pagi' : j < 15 ? 'Selamat siang' : j < 18 ? 'Selamat sore' : 'Selamat malam',
  };
}
