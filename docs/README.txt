= Kateglo =

Kateglo adalah aplikasi web sumber terbuka untuk kamus, tesaurus, dan
glosarium bahasa Indonesia. Namanya diambil dari singkatan unsur layanannya,
KAmus, TEsaurus, dan GLOsarium.

Lisensi kode sumber Kateglo adalah GPL, sedangkan lisensi isinya adalah
CC-BY-NC-SA. Kode sumber program dapat diunduh dari repositori publik:

	http://www.bahtera.org/svn/kateglo/

Setelah mengunduh aplikasi, silakan baca INSTALL.txt di direktori docs untuk
petunjuk instalasi aplikasi dan LICENSE.txt untuk lisensi GNU GPL.

== Kredit ==

* Ivan Lanin - ivan at lanin dot org
* Romi Hardiyanto - http://www.ewesewes.web.id
* Femmy Syahrani - http://femmy.multiply.com
* Anggota milis Bahtera - http://groups.yahoo.com/group/bahtera

== Standar kode pemrograman ==

* Gunakan huruf kecil dengan garis bawah sebagai pemisah kata.
* Gunakan kurung kurawa pada baris terpisah untuk blok fungsi, kondisi, dll.
* Jika blok hanya berisi satu baris, hindari kurung kurawa.
* Gunakan tab untuk mengatur indentasi kode.
* Batasi baris (termasuk indentasi) hingga 76 karakter.

== Daftar pekerjaan ==

* Klasifikasi Roget untuk glosarium (Femmy)
* Parser otomatis untuk data KBBI
* Penambahan ungkapan sebagai unsur tesaurus -> contohnya apa ya?
* Pendaftaran pengguna
* Preferensi pengguna
* Pembalikan rujukan untuk data yang ditemukan (imbuhan, majemuk)
* Satu frasa yang memiliki dua kelas kata
* Penambahan definisi secara dinamis (AJAX?)
* Satu kotak pencarian umum (pencarian detil untuk yang lain)
* Entri untuk referensi
* Penambahan/penyuntingan glosarium secara sekaligus
* Statistik pengguna teraktif
* API atau antarmuka pemrograman aplikasi
* ki = kiasan?
* Kata hari ini

== Riwayat revisi ==

=== 0.0.6 (17 Mei 2009) ===

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