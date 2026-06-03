import React from 'react';
import { motion } from 'framer-motion';
import { GridPattern } from './ui/grid-pattern';
import { ArrowRight } from 'lucide-react';
import { cn } from '@/lib/utils';
import { LinkCard } from './ui/link-card';

const steps = [
  {
    num: '01',
    title: 'Pilih Produk',
    desc: 'Jelajahi katalog kami dan pilih produk sesuai kebutuhan bisnis Anda.',
    imageUrl: 'https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Shopping%20Cart.png',
  },
  {
    num: '02',
    title: 'Upload Desain',
    desc: 'Upload file desain (JPG, PNG, PDF, AI) langsung dari akun Anda.',
    imageUrl: 'https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Activities/Artist%20Palette.png',
  },
  {
    num: '03',
    title: 'Pembayaran',
    desc: 'Bayar via transfer bank dan unggah bukti pembayaran dengan aman.',
    imageUrl: 'https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Credit%20Card.png',
  },
  {
    num: '04',
    title: 'Terima Pesanan',
    desc: 'Pesanan dicetak berkualitas dan siap diambil atau dikirim.',
    imageUrl: 'https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Package.png',
  },
];

export default function CaraOrderSection() {
  const containerVariants = {
    hidden: { opacity: 0 },
    visible: {
      opacity: 1,
      transition: { staggerChildren: 0.15 },
    },
  };

  const itemVariants = {
    hidden: { opacity: 0, y: 20 },
    visible: { opacity: 1, y: 0, transition: { duration: 0.5, ease: 'easeOut' } },
  };

  return (
    <section id="cara-order" className="relative py-10 lg:py-12 overflow-hidden bg-slate-50">
      <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center max-w-2xl mx-auto mb-16 lg:mb-20">
          <motion.p
            initial={{ opacity: 0, y: 10 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            className="text-xs font-bold uppercase tracking-widest text-blue-600 mb-3"
          >
            PROSEDUR
          </motion.p>
          <motion.h2
            initial={{ opacity: 0, y: 10 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ delay: 0.1 }}
            className="font-display text-3xl sm:text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-emerald-500 tracking-tight"
          >
            Cara Order Mudah
          </motion.h2>
        </div>

        <motion.div
          variants={containerVariants}
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true, margin: "-100px" }}
          className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8"
        >
          {steps.map((step) => (
            <motion.div key={step.num} variants={itemVariants} className="w-full">
              <LinkCard
                title={step.title}
                description={step.desc}
                imageUrl={step.imageUrl}
                stepNum={step.num}
              />
            </motion.div>
          ))}
        </motion.div>

        <motion.div
          initial={{ opacity: 0, scale: 0.95 }}
          whileInView={{ opacity: 1, scale: 1 }}
          viewport={{ once: true }}
          transition={{ delay: 0.5 }}
          className="text-center mt-20"
        >
          <a
            href="/katalog"
            className="inline-flex items-center gap-2 px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm shadow-lg shadow-blue-600/25 transition-all hover:-translate-y-0.5 active:scale-95"
          >
            Mulai Order Sekarang
            <ArrowRight className="w-4 h-4" />
          </a>
        </motion.div>
      </div>
    </section>
  );
}
