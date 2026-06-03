"use client";

import React, { useRef, useState } from "react";
import { Button } from "@/components/ui/button";
import {
  Dialog,
  DialogClose,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog";

export function TermsDialog({ children }) {
  const [hasReadToBottom, setHasReadToBottom] = useState(false);
  const contentRef = useRef(null);

  const handleScroll = () => {
    const content = contentRef.current;
    if (!content) return;

    const scrollPercentage = content.scrollTop / (content.scrollHeight - content.clientHeight);
    if (scrollPercentage >= 0.99 && !hasReadToBottom) {
      setHasReadToBottom(true);
    }
  };

  return (
    <Dialog>
      <DialogTrigger asChild>
        {children}
      </DialogTrigger>
      <DialogContent className="flex flex-col gap-0 p-0 sm:max-h-[min(640px,80vh)] sm:max-w-lg [&>button:last-child]:top-3.5 bg-white overflow-hidden">
        <DialogHeader className="contents space-y-0 text-left">
          <DialogTitle className="border-b border-slate-200 px-6 py-4 text-base bg-white text-slate-900">
            Syarat & Ketentuan Layanan
          </DialogTitle>
          <div ref={contentRef} onScroll={handleScroll} className="overflow-y-auto bg-white text-slate-900">
            <DialogDescription asChild>
              <div className="px-6 py-4">
                <div className="space-y-4 [&_strong]:font-semibold [&_strong]:text-slate-900">
                  <div className="space-y-4 text-sm text-slate-600">
                    <p className="text-sm font-medium text-slate-500 bg-slate-50 p-4 rounded-lg">
                      Dengan mengakses dan menggunakan layanan pemesanan melalui website Jaya Mandiri Digital Printing, Anda dianggap telah membaca, memahami, dan menyetujui seluruh syarat dan ketentuan yang berlaku.
                    </p>
                    
                    <div className="space-y-1">
                      <p>
                        <strong>1. Ketentuan Umum</strong>
                      </p>
                      <ul className="list-disc pl-6 space-y-1">
                        <li>Layanan printing online Jaya Mandiri Digital Printing (selanjutnya disebut "Jaya Mandiri") terbuka untuk perorangan maupun badan usaha.</li>
                        <li>Jam operasional pelayanan online adalah Senin-Sabtu, pukul 08:00 - 17:00 WIB. Pesanan di luar jam kerja akan diproses pada hari kerja berikutnya.</li>
                        <li>Jaya Mandiri berhak menolak pesanan yang mengandung unsur SARA, pornografi, ujaran kebencian, atau hal-hal yang melanggar hukum di Indonesia.</li>
                      </ul>
                    </div>

                    <div className="space-y-1">
                      <p>
                        <strong>2. Ketentuan File Desain</strong>
                      </p>
                      <ul className="list-disc pl-6 space-y-1">
                        <li>File yang dikirimkan harus berformat PDF, JPG, PNG, atau format vektor (AI, CDR, EPS) dengan resolusi minimum 300 DPI (untuk cetakan kertas) atau 150 DPI (untuk format besar/spanduk).</li>
                        <li>Mode warna yang digunakan harus CMYK. Apabila file yang dikirimkan menggunakan mode RGB, Jaya Mandiri akan mengonversinya secara otomatis, yang mungkin mengakibatkan sedikit pergeseran warna.</li>
                        <li>Jaya Mandiri tidak bertanggung jawab atas kesalahan cetak yang diakibatkan oleh: file resolusi rendah (pecah/blur), kesalahan pengetikan (typo) pada desain Anda, atau font yang tidak di-convert ke kurva/outline.</li>
                      </ul>
                    </div>

                    <div className="space-y-1">
                      <p>
                        <strong>3. Ketentuan Produksi & Warna</strong>
                      </p>
                      <ul className="list-disc pl-6 space-y-1">
                        <li>Hasil cetak akhir tidak akan 100% sama dengan warna yang terlihat di layar monitor Anda karena perbedaan kalibrasi layar dan profil mesin cetak. Toleransi perbedaan warna adalah ± 5-10%.</li>
                        <li>Estimasi waktu produksi yang tertera adalah perkiraan. Keterlambatan produksi yang disebabkan oleh force majeure (kerusakan mesin massal, listrik padam, dsb.) akan kami informasikan secepatnya kepada pelanggan.</li>
                      </ul>
                    </div>

                    <div className="space-y-1">
                      <p>
                        <strong>4. Pembayaran & Harga</strong>
                      </p>
                      <ul className="list-disc pl-6 space-y-1">
                        <li>Pesanan hanya akan diproduksi setelah pembayaran lunas terverifikasi.</li>
                        <li>Pembayaran yang sudah dilakukan tidak dapat dibatalkan (non-refundable) apabila pesanan sudah masuk antrean naik cetak.</li>
                        <li>Harga yang tertera di website dapat berubah sewaktu-waktu tanpa pemberitahuan sebelumnya. Harga mengikat setelah invoice diterbitkan.</li>
                      </ul>
                    </div>

                    <div className="space-y-1">
                      <p>
                        <strong>5. Pengiriman & Pengambilan</strong>
                      </p>
                      <ul className="list-disc pl-6 space-y-1">
                        <li>Keterlambatan atau kerusakan barang akibat pihak jasa ekspedisi berada di luar tanggung jawab Jaya Mandiri. Namun, kami akan membantu proses klaim kepada pihak ekspedisi terkait.</li>
                        <li>Pesanan dengan opsi ambil di toko (pickup) harap diambil maksimal 30 hari sejak notifikasi selesai. Melewati batas waktu tersebut, Jaya Mandiri berhak untuk melakukan pemusnahan tanpa pengembalian dana.</li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </DialogDescription>
          </div>
        </DialogHeader>
        <DialogFooter className="border-t border-slate-200 bg-slate-50 px-6 py-4 sm:items-center">
          {!hasReadToBottom && (
            <span className="grow text-xs text-slate-500 max-sm:text-center">
              Gulir sampai ke bawah untuk melanjutkan.
            </span>
          )}
          <DialogClose asChild>
            <Button type="button" variant="outline" className="border-slate-300">
              Batal
            </Button>
          </DialogClose>
          <DialogClose asChild>
            <Button type="button" className="bg-blue-600 hover:bg-blue-700 text-white disabled:bg-slate-200 disabled:text-slate-400 disabled:opacity-100" disabled={!hasReadToBottom}>
              Saya Setuju
            </Button>
          </DialogClose>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
}
