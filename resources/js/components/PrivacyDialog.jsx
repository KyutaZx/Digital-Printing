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

export function PrivacyDialog({ children }) {
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
            Kebijakan Privasi
          </DialogTitle>
          <div ref={contentRef} onScroll={handleScroll} className="overflow-y-auto bg-white text-slate-900">
            <DialogDescription asChild>
              <div className="px-6 py-4">
                <div className="space-y-4 [&_strong]:font-semibold [&_strong]:text-slate-900">
                  <div className="space-y-4 text-sm text-slate-600">
                    <p className="text-sm font-medium text-slate-500 bg-slate-50 p-4 rounded-lg">
                      Bagaimana kami melindungi dan mengelola data Anda. Terakhir diperbarui: 25 April 2026.
                    </p>
                    
                    <div className="space-y-1">
                      <p>
                        <strong>1. Pengumpulan Informasi Personal</strong>
                      </p>
                      <p>
                        Kami mengumpulkan informasi yang Anda berikan secara langsung kepada kami saat mendaftar akun, melakukan pemesanan, atau menghubungi layanan pelanggan. Informasi ini meliputi nama, alamat email, nomor telepon, alamat pengiriman, dan file desain cetak.
                      </p>
                    </div>

                    <div className="space-y-1">
                      <p>
                        <strong>2. Penggunaan Informasi</strong>
                      </p>
                      <p>Kami menggunakan informasi yang kami kumpulkan untuk:</p>
                      <ul className="list-disc pl-6 space-y-1">
                        <li>Memproses dan menyelesaikan pesanan percetakan Anda.</li>
                        <li>Berkomunikasi dengan Anda mengenai status pesanan, revisi desain, atau kendala pengiriman.</li>
                        <li>Mengirimkan informasi teknis, pembaruan keamanan, dan dukungan operasional.</li>
                      </ul>
                    </div>

                    <div className="space-y-1">
                      <p>
                        <strong>3. Perlindungan File Desain</strong>
                      </p>
                      <p>
                        File desain yang Anda unggah murni digunakan untuk keperluan cetak pesanan Anda. Kami menjamin kerahasiaan kekayaan intelektual (HAKI) dari desain tersebut dan tidak akan memperjualbelikannya kepada pihak ketiga atau menggunakannya sebagai sampel promosi tanpa izin tertulis dari Anda.
                      </p>
                    </div>

                    <div className="space-y-1">
                      <p>
                        <strong>4. Penyimpanan Data</strong>
                      </p>
                      <p>
                        Kami menyimpan informasi profil Anda selama akun Anda aktif. Untuk file desain resolusi tinggi, kami akan menghapusnya dari server kami 30 hari setelah pesanan selesai untuk menghemat ruang penyimpanan, kecuali Anda memintanya untuk disimpan lebih lama.
                      </p>
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
              Saya Mengerti
            </Button>
          </DialogClose>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
}
