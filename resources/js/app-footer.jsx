import '../css/app.css';
import React from 'react';
import { createRoot } from 'react-dom/client';
import { FooterSection } from './components/ui/FooterSection';

const rootEl = document.getElementById('app-footer-root');

if (rootEl) {
  createRoot(rootEl).render(<FooterSection />);
}
