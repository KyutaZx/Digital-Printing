import '../css/app.css';
import React from 'react';
import { createRoot } from 'react-dom/client';
import { PulseFitHero } from './components/ui/PulseFitHero';

function navigate(href) {
  if (href?.startsWith('#')) {
    const el = document.querySelector(href);
    if (el) {
      el.scrollIntoView({ behavior: 'smooth' });
      return;
    }
  }
  window.location.href = href || '/';
}

const rootEl = document.getElementById('landing-hero-root');
const propsEl = document.getElementById('landing-hero-props');

if (rootEl && propsEl) {
  const data = JSON.parse(propsEl.textContent);

  const programs = (data.programs || []).map((p) => ({
    ...p,
    onClick: () => navigate(p.href),
  }));

  createRoot(rootEl).render(
    <PulseFitHero
      hideHeader
      title={data.title}
      titleHighlight={data.titleHighlight}
      subtitle={data.subtitle}
      primaryAction={{
        label: data.primaryAction?.label,
        onClick: () => navigate(data.primaryAction?.href),
      }}
      secondaryAction={{
        label: data.secondaryAction?.label,
        onClick: () => navigate(data.secondaryAction?.href),
      }}
      disclaimer={data.disclaimer}
      socialProof={data.socialProof}
      programs={programs}
    />
  );
}
