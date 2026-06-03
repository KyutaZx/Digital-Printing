import React from 'react';
import { createRoot } from 'react-dom/client';
import Accordion_02 from './components/ui/ruixen-accordian02';
import '../css/app.css';

const rootElement = document.getElementById('about-faq-root');
if (rootElement) {
    const root = createRoot(rootElement);
    root.render(
        <React.StrictMode>
            <Accordion_02 />
        </React.StrictMode>
    );
}
