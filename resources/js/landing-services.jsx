import '../css/app.css';
import React from 'react';
import { createRoot } from 'react-dom/client';
import { ExpandingCards } from './components/ui/expanding-cards';
import { AnimatedText } from './components/ui/animated-underline-text-one';
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

const getCategoryIcon = (categoryName) => {
  const name = categoryName.toLowerCase();
  if (name.includes('banner') || name.includes('spanduk')) return Flag;
  if (name.includes('brosur') || name.includes('flyer')) return Tag;
  if (name.includes('kartu') || name.includes('card')) return CreditCard;
  if (name.includes('kalender') || name.includes('calendar')) return Calendar;
  if (name.includes('kaos') || name.includes('baju')) return Shirt;
  if (name.includes('kemasan') || name.includes('dus') || name.includes('box') || name.includes('packaging')) return Package;
  if (name.includes('kanvas') || name.includes('canvas')) return Palette;
  if (name.includes('buku') || name.includes('majalah')) return ImageIcon;
  return Tag;
};

const getFallbackImage = (name) => {
  const initial = name ? name.charAt(0).toUpperCase() : 'P';
  const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="800" height="600" viewBox="0 0 800 600">
    <rect width="800" height="600" fill="#3b82f6"/>
    <text x="400" y="300" font-family="Arial, sans-serif" font-size="200" font-weight="bold" fill="#ffffff" text-anchor="middle" dominant-baseline="central">${initial}</text>
  </svg>`;
  return `data:image/svg+xml;base64,${btoa(svg)}`;
};

const titleRootEl = document.getElementById('landing-services-title-root');
const rootEl = document.getElementById('landing-services-root');
const dataScript = document.getElementById('landing-services-data');

let servicesItems = [];
if (dataScript) {
  try {
    const rawData = JSON.parse(dataScript.textContent);
    servicesItems = rawData.map(item => {
      const IconComp = getCategoryIcon(item.title);
      return {
        id: item.id || item.title.toLowerCase().replace(/\s+/g, '-'),
        title: item.title,
        description: item.description,
        imgSrc: item.imgSrc || getFallbackImage(item.title),
        icon: <IconComp size={28} />,
        linkHref: item.linkHref || `/katalog?category=${encodeURIComponent(item.title)}`,
      };
    });
  } catch (e) {
    console.error('Failed to parse services data', e);
  }
}

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

if (rootEl) {
  createRoot(rootEl).render(
    <ExpandingCards items={servicesItems} defaultActiveIndex={0} />
  );
}
