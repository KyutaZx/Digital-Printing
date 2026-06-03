import React from "react";
import {
  Accordion,
  AccordionContent,
  AccordionItem,
  AccordionTrigger,
} from "@/components/ui/accordion";

export default function Accordion_02() {
  return (
    <div className="w-full max-w-6xl mx-auto px-4 py-20">
      <div className="flex flex-col md:flex-row gap-12 items-start">
        {/* Left Column */}
        <div className="md:w-1/2">
          <h2 className="text-4xl font-bold mb-4 text-slate-900">FAQ & Pertanyaan Umum</h2>
          <p className="text-slate-600 text-lg">
            Kami siap membantu Anda memahami bagaimana semuanya bekerja. Jika Anda masih memiliki
            pertanyaan, jangan ragu untuk{" "}
            <a href="/#kontak" className="underline text-blue-600 hover:text-blue-700 font-semibold">
              hubungi tim kami
            </a>
            .
          </p>
        </div>

        {/* Right Column */}
        <div className="md:w-1/2 space-y-10">
          {/* General Section */}
          <div>
            <h3 className="text-lg font-semibold text-slate-500 mb-2">
              Umum
            </h3>
            <Accordion type="multiple" className="w-full text-slate-700">
              <AccordionItem value="gen-1">
                <AccordionTrigger>
                  Apa keunggulan cetak di Jaya Mandiri?
                </AccordionTrigger>
                <AccordionContent>
                  Kami menawarkan proses cepat, kualitas mesin cetak terbaik (high-res), harga yang kompetitif, dan layanan pelanggan yang siap membantu kapan saja.
                </AccordionContent>
              </AccordionItem>
              <AccordionItem value="gen-2">
                <AccordionTrigger>
                  Apakah melayani pengiriman ke seluruh Indonesia?
                </AccordionTrigger>
                <AccordionContent>
                  Ya, kami bekerjasama dengan berbagai ekspedisi terpercaya untuk melayani pengiriman ke seluruh wilayah di Indonesia dengan aman.
                </AccordionContent>
              </AccordionItem>
            </Accordion>
          </div>

          {/* Billing Section */}
          <div>
            <h3 className="text-lg font-semibold text-slate-500 mb-2">
              Pemesanan & Pembayaran
            </h3>
            <Accordion type="multiple" className="w-full text-slate-700">
              <AccordionItem value="bill-1">
                <AccordionTrigger>
                  Metode pembayaran apa saja yang diterima?
                </AccordionTrigger>
                <AccordionContent>
                  Saat ini kami menerima pembayaran melalui Transfer Bank (BCA, Mandiri, BNI) dan dompet digital (OVO, GoPay, Dana) dengan verifikasi bukti transfer.
                </AccordionContent>
              </AccordionItem>
              <AccordionItem value="bill-2">
                <AccordionTrigger>
                  Apakah ada minimal pemesanan?
                </AccordionTrigger>
                <AccordionContent>
                  Minimal pemesanan bervariasi tergantung produk. Untuk brosur dan kartu nama umumnya ada kuantiti minimal, namun untuk spanduk atau banner, kami melayani pesanan walau hanya 1 meter.
                </AccordionContent>
              </AccordionItem>
            </Accordion>
          </div>

          {/* Technical Section */}
          <div>
            <h3 className="text-lg font-semibold text-slate-500 mb-2">
              Desain & Teknis
            </h3>
            <Accordion type="multiple" className="w-full text-slate-700">
              <AccordionItem value="tech-1">
                <AccordionTrigger>
                  Format file desain apa yang disarankan?
                </AccordionTrigger>
                <AccordionContent>
                  Untuk hasil terbaik, kami menyarankan format file vektor seperti PDF, Adobe Illustrator (AI), atau CorelDraw (CDR). Namun, format gambar hi-res seperti JPG dan PNG (min 300dpi) juga bisa digunakan.
                </AccordionContent>
              </AccordionItem>
              <AccordionItem value="tech-2">
                <AccordionTrigger>
                  Bagaimana jika foto saya buram (blur)?
                </AccordionTrigger>
                <AccordionContent>
                  Sistem AI kami akan secara otomatis mendeteksi apakah file gambar yang Anda unggah terlalu buram sebelum diproses, sehingga Anda bisa menggantinya dengan resolusi yang lebih baik.
                </AccordionContent>
              </AccordionItem>
            </Accordion>
          </div>
        </div>
      </div>
    </div>
  );
}
