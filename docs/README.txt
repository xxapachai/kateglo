= Kateglo =

Kateglo adalah aplikasi web sumber terbuka untuk kamus, tesaurus, dan
glosarium bahasa Indonesia. Namanya diambil dari singkatan unsur layanannya,
KAmus, TEsaurus, dan GLOsarium.

Lisensi kode sumber Kateglo adalah GPL. Kode sumber program dapat diunduh
dari repositori publik:

	http://www.bahtera.org/svn/kateglo/

Setelah mengunduh aplikasi, silakan baca INSTALL.txt di direktori docs untuk
petunjuk instalasi aplikasi dan LICENSE.txt untuk lisensi GNU GPL.

Lisensi isi Kateglo adalah CC-BY-NC-SA kecuali yang disebutkan di bawah ini.

Data dari Pusat Bahasa Departemen Pendidikan Nasional Indonesia - ditandai
dengan "Pusba" atau "Pusat Bahasa" - merupakan hak cipta dari Pusat Bahasa
dan dipergunakan di Kateglo dengan seizin Pusba. Izin spesifik untuk
melisensikan di bawah lisensi CC-BY-NC-SA belum diperoleh dan karenanya
sebaiknya berhati-hati menggunakannya.

== Kredit ==

* Ivan Lanin - ivan at lanin dot org
* Romi Hardiyanto - http://www.ewesewes.web.id
* Femmy Syahrani - http://femmy.multiply.com
* Steven Haryanto - http://steven.blogs.masterweb.net
* Pusat Bahasa - http://pusatbahasa.diknas.go.id
* Seluruh anggota milis Bahtera - http://groups.yahoo.com/group/bahtera

== Standar kode program ==

* Gunakan huruf kecil dengan garis bawah sebagai pemisah kata.
* Gunakan kurung kurawa pada baris terpisah untuk blok fungsi, kondisi, dll.
* Jika blok hanya berisi satu baris, hindari kurung kurawa.
* Gunakan tab (4 karakter) untuk mengatur indentasi kode.
* Usahakan batasi baris (termasuk indentasi) hingga 76 karakter.

== Daftar pekerjaan ==

* Satu kotak pencarian umum (pencarian detil untuk yang lain)
* Klasifikasi Roget untuk glosarium (Femmy)
* Penambahan ungkapan sebagai unsur tesaurus -> contohnya apa ya?
* Pendaftaran pengguna
* Preferensi pengguna
* Satu frasa yang memiliki dua kelas kata
* Penambahan/penyuntingan glosarium secara sekaligus
* Entri untuk referensi
* Penambahan definisi secara dinamis (AJAX?)
* Statistik pengguna teraktif
* API atau antarmuka pemrograman aplikasi
* ki = kiasan?
* Kata hari ini

== Bug yang diketahui ==

* Algoritma parser KBBI belum efisien
* Kesalahan parsing KBBI: "uang", "tuju", "jabat", "areal"
* Kesalahan parsing KBBI: sufiks "1" pada kata gabungan
* Akar kata yang sama dengan frasanya
* Suatu kata tertentu yang belum ada di kamus sulit dicari jika pencarian
  menghasilkan kata lain. Contoh "kau" sulit diakses jika ada kata "kaum"
  --> Diselesaikan dengan menampilkan pranala ke frasa yang dicari.

== Riwayat revisi ==

=== 0.0.8 (22 Mei 2009) ===

* Penambahan phrase.ref_source
* Log waktu pembuatan entri kamus secara otomatis

=== 0.0.7 (21 Mei 2009) ===

* Parser untuk sinonim
* Perbaikan bug jika frasa pencarian mengandung karakter escape sql
* Menampilkan frasa yang dicari di navigasi halaman hasil pencarian.
* Perubahan tampilan daftar
* Parser otomatis untuk data KBBI
* Pembalikan rujukan untuk data yang ditemukan (imbuhan, majemuk)

=== 0.0.6 (17 Mei 2009) ===

* Klasifikasi Roget dimasukkan ke dalam entri
* Pengalihan otomatis untuk pencarian kamus jika tidak ditemukan data
* Log jenis agen penjelajah dan waktu terakhir akses serta jumlah tampilan
  halaman untuk statistik
* Kredit untuk Femmy Syahrani
* Perubahan lisensi isi ke CC-BY-NC-SA

=== 0.0.5 (16 Mei 2009) ===

* Formulir login dipindahkan ke class_user
* Menampilkan daftar kata yang ada dalam kamus
* Memasukkan peribahasa sebagai salah satu unsur tesaurus
* Log dibatasi hanya $_SERVER['QUERY_STRING']
* Kelas phrase -> dictionary
* Penggantian kata sandi pengguna

=== 0.0.4 (15 Mei 2009) ===

* Rujukan ke Wikipedia untuk glosarium
* Sumber glosarium
* Daftar glosarium terkait pada entri kamus
* Fungsi pengubahan data pembaru dan waktu pembaruan untuk entri kamus dan glosarium
* Penambahan dan penyuntingan glosarium sederhana
* Melengkapi kelas kata menjadi 7: nomina, verba, adjektiva, adverbia, pronomina, numeralia, lain-lain
* Kata kunci di glosarium yang merujuk ke entri kamus
* Rujukan ke KBBI di kamus

=== 0.0.3 (14 Mei 2009) ===

* Halaman muka yang sesuai
* Statistik pencarian frasa
* mod=doc untuk membaca dokumen
* Pembagian halaman untuk hasil pencarian

=== 0.0.2 (13 Mei 2009) ===

* Glosarium sederhana ditambahkan
* Log deskripsi diperpendek menjadi hanya nama skrip dan querystring
* Favicon dan logo
* Menggabungkan relasi dan turunan menjadi tesaurus
* Implementasi log dasar
* Implementasi otentikasi dasar
* Memangkas nilai terkirim sebelum memberi kutipan
* Kelas untuk frasa

=== 0.0.1 (12 Mei 2009) ===

* 2009-05-12 Versi pertama dirilis dan dimasukkan kendali sumber.
* 2009-05-09 Konsep didiskusikan melalui obrolan YM.