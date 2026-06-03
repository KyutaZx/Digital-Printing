import '../css/app.css';
import React from 'react';
import { createRoot } from 'react-dom/client';
import { ExpandingCards } from './components/ui/expanding-cards';
import { 
  Tag, 
  Flag, 
  Image as ImageIcon, 
  CreditCard, 
  Calendar, 
  Shirt, 
  Package, 
  Palette 
} from 'lucide-react';

const servicesItems = [
  {
    id: 'sticker-custom',
    title: 'Sticker Custom',
    description: 'Vinyl waterproof untuk branding produk, kemasan makanan, dekorasi dinding, & branding kendaraan.',
    imgSrc: 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&auto=format&fit=crop&q=80',
    icon: <Tag size={28} />,
    linkHref: '/katalog?q=sticker',
  },
  {
    id: 'banner-outdoor',
    title: 'Banner Outdoor',
    description: 'Flexi Tiongkok/Korea premium tahan cuaca panas & hujan untuk media promosi luar ruang toko Anda.',
    imgSrc: 'https://images.unsplash.com/photo-1611532736597-de2d4265fba3?w=800&auto=format&fit=crop&q=80',
    icon: <Flag size={28} />,
    linkHref: '/katalog?q=banner',
  },
  {
    id: 'spanduk',
    title: 'Spanduk',
    description: 'Cetak spanduk event, promosi diskon toko, & umbul-umbul dengan finishing ring mata ayam yang rapi.',
    imgSrc: 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=800&auto=format&fit=crop&q=80',
    icon: <ImageIcon size={28} />,
    linkHref: '/katalog?q=spanduk',
  },
  {
    id: 'kartu-nama',
    title: 'Kartu Nama',
    description: 'Berbagai jenis kertas & finishing premium: matte/doff, glossy, kartu nama transparan, dan spot UV.',
    imgSrc: 'https://images.unsplash.com/photo-1541701494587-cb58502866ab?w=800&auto=format&fit=crop&q=80',
    icon: <CreditCard size={28} />,
    linkHref: '/katalog?q=kartu',
  },
  {
    id: 'kalender',
    title: 'Kalender',
    description: 'Kalender meja & kalender dinding custom dengan pilihan jilid spiral untuk hadiah eksklusif akhir tahun.',
    imgSrc: 'https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?w=800&auto=format&fit=crop&q=80',
    icon: <Calendar size={28} />,
    linkHref: '/katalog?q=kalender',
  },
  {
    id: 'kaos-printing',
    title: 'Kaos Printing',
    description: 'Cetak kaos sablon DTF (Direct to Film) & sublimasi dengan detail warna tajam, lentur, dan tahan lama.',
    imgSrc: 'https://images.unsplash.com/photo-1503341455253-b2e723bb3dbb?w=800&auto=format&fit=crop&q=80',
    icon: <Shirt size={28} />,
    linkHref: '/katalog?q=kaos',
  },
  {
    id: 'kemasan',
    title: 'Kemasan',
    description: 'Dus box makanan, kemasan standing pouch, & packaging corrugated custom untuk menaikkan nilai UMKM.',
    imgSrc: 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&auto=format&fit=crop&q=80',
    icon: <Package size={28} />,
    linkHref: '/katalog?q=kemasan',
  },
  {
    id: 'kanvas-print',
    title: 'Kanvas Print',
    description: 'Cetak foto keluarga, lukisan, & artwork berkualitas museum di media kain kanvas bertekstur premium.',
    imgSrc: 'https://images.unsplash.com/photo-1578301978693-85fa9c0320b9?w=800&auto=format&fit=crop&q=80',
    icon: <Palette size={28} />,
    linkHref: '/katalog?q=kanvas',
  },
];

const bgRootEl = document.getElementById('landing-global-bg-root');
if (bgRootEl) {
  // Global grid background removed as requested by user
}

import { AnimatedText } from './components/ui/animated-underline-text-one';

const titleRootEl = document.getElementById('landing-services-title-root');
if (titleRootEl) {
  createRoot(titleRootEl).render(
    <AnimatedText 
      text="Semua Kebutuhan Cetak Anda" 
      textClassName="font-display text-3xl sm:text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-emerald-500 tracking-tight pb-1"
      underlineClassName="text-emerald-500"
      underlineDuration={1.5}
    />
  );
}

const rootEl = document.getElementById('landing-services-root');

if (rootEl) {
  createRoot(rootEl).render(
    <ExpandingCards items={servicesItems} defaultActiveIndex={0} />
  );
}
