import React from 'react';
import { createRoot } from 'react-dom/client';
import CaraOrderSection from './components/CaraOrderSection';
import '../css/app.css'; // Optional: import tailwind

const rootElement = document.getElementById('cara-order-root');
if (rootElement) {
    const root = createRoot(rootElement);
    root.render(
        <React.StrictMode>
            <CaraOrderSection />
        </React.StrictMode>
    );
}
