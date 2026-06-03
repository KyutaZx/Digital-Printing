import React from 'react';
import { motion, useReducedMotion } from 'framer-motion';
import { Globe, Mail, MessageCircle, Share2 } from 'lucide-react';
import { cn } from '../../lib/utils';
import { TermsDialog } from '../TermsDialog';
import { PrivacyDialog } from '../PrivacyDialog';

const defaultFooterLinks = [
  {
    label: 'Layanan',
    links: [
      { title: 'Banner Outdoor', href: '/katalog' },
      { title: 'Spanduk', href: '/katalog' },
      { title: 'Sticker Custom', href: '/katalog' },
      { title: 'Kartu Nama', href: '/katalog' },
    ],
  },
  {
    label: 'Perusahaan',
    links: [
      { title: 'Tentang Kami', href: '/tentang' },
      { title: 'Cara Order', href: '/cara-order' },
      { title: 'Katalog', href: '/katalog' },
      { title: 'Kontak', href: '/#kontak' },
    ],
  },
  {
    label: 'Hukum',
    links: [
      { title: 'Syarat & Ketentuan', href: '/syarat-ketentuan' },
      { title: 'Kebijakan Privasi', href: '/kebijakan-privasi' },
    ],
  },
  {
    label: 'Sosial Media',
    links: [
      { title: 'Facebook', href: '#', icon: Share2 },
      { title: 'Instagram', href: '#', icon: Globe },
      { title: 'WhatsApp', href: '#', icon: MessageCircle },
      { title: 'Email', href: 'mailto:halo@jayamandiri.com', icon: Mail },
    ],
  },
];

function AnimatedContainer({ className, delay = 0.1, children }) {
  const shouldReduceMotion = useReducedMotion();

  if (shouldReduceMotion) {
    return <div className={className}>{children}</div>;
  }

  return (
    <motion.div
      initial={{ filter: 'blur(4px)', translateY: -8, opacity: 0 }}
      whileInView={{ filter: 'blur(0px)', translateY: 0, opacity: 1 }}
      viewport={{ once: true }}
      transition={{ delay, duration: 0.8 }}
      className={className}
    >
      {children}
    </motion.div>
  );
}

export function FooterSection({
  brandName = 'Jaya Mandiri',
  tagline = 'Solusi digital printing berkualitas tinggi untuk kebutuhan bisnis dan personal Anda.',
  sections = defaultFooterLinks,
  className,
}) {
  const year = new Date().getFullYear();

  return (
    <footer
      className={cn(
        'relative mx-auto flex w-full max-w-6xl flex-col items-center justify-center rounded-t-[2rem] border-t border-slate-800 bg-slate-950 bg-[radial-gradient(35%_128px_at_50%_0%,rgba(37,99,235,0.18),transparent)] px-6 py-12 lg:rounded-t-[3rem] lg:py-16',
        className
      )}
    >
      <div className="absolute top-0 right-1/2 left-1/2 h-px w-1/3 -translate-x-1/2 -translate-y-1/2 rounded-full bg-primary-500/40 blur-sm" />

      <div className="grid w-full gap-8 xl:grid-cols-3 xl:gap-8">
        <AnimatedContainer className="space-y-4">
          <div className="flex items-center gap-2.5">
            <div className="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-primary-500 to-emerald-500 text-sm font-bold text-white shadow-md">
              J
            </div>
            <span className="text-lg font-bold text-white">{brandName}</span>
          </div>
          <p className="mt-4 max-w-xs text-sm leading-relaxed text-slate-400 md:mt-2">
            {tagline}
          </p>
          <p className="text-sm text-slate-500">
            © {year} {brandName} Digital Printing. Hak Cipta Dilindungi.
          </p>
        </AnimatedContainer>

        <div className="mt-10 grid grid-cols-2 gap-8 md:grid-cols-4 xl:col-span-2 xl:mt-0">
          {sections.map((section, index) => (
            <AnimatedContainer key={section.label} delay={0.1 + index * 0.1}>
              <div className="mb-10 md:mb-0">
                <h3 className="text-xs font-semibold uppercase tracking-wider text-slate-200">
                  {section.label}
                </h3>
                <ul className="mt-4 space-y-2 text-sm text-slate-400">
                  {section.links.map((link) => {
                    const Icon = link.icon;
                    
                    if (link.title === 'Syarat & Ketentuan') {
                      return (
                        <li key={link.title}>
                          <TermsDialog>
                            <button type="button" className="inline-flex items-center transition-all duration-300 hover:text-white cursor-pointer">
                              {Icon && <Icon className="me-1.5 size-4" />}
                              {link.title}
                            </button>
                          </TermsDialog>
                        </li>
                      );
                    }

                    if (link.title === 'Kebijakan Privasi') {
                      return (
                        <li key={link.title}>
                          <PrivacyDialog>
                            <button type="button" className="inline-flex items-center transition-all duration-300 hover:text-white cursor-pointer">
                              {Icon && <Icon className="me-1.5 size-4" />}
                              {link.title}
                            </button>
                          </PrivacyDialog>
                        </li>
                      );
                    }

                    return (
                      <li key={link.title}>
                        <a
                          href={link.href}
                          className="inline-flex items-center transition-all duration-300 hover:text-white"
                        >
                          {Icon && <Icon className="me-1.5 size-4" />}
                          {link.title}
                        </a>
                      </li>
                    );
                  })}
                </ul>
              </div>
            </AnimatedContainer>
          ))}
        </div>
      </div>
    </footer>
  );
}
