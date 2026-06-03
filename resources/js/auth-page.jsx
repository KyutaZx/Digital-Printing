import React from 'react';
import { createRoot } from 'react-dom/client';
import { AuthFormSplitScreen } from './components/ui/AuthFormSplitScreen';

const rootEl = document.getElementById('auth-root');
const propsEl = document.getElementById('auth-props');

if (rootEl && propsEl) {
  const data = JSON.parse(propsEl.textContent);
  createRoot(rootEl).render(<AuthFormSplitScreen {...data} />);
}
