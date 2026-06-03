import React from 'react';
import { createRoot } from 'react-dom/client';
import { PlaceCard } from './components/ui/card-22';
import { Toaster } from 'react-hot-toast';

function CatalogGrid({ products, apiUrl }) {
  // Format currency
  const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID', {
      minimumFractionDigits: 0
    }).format(price);
  };

  const getFallbackImage = (name) => {
    const initial = name ? name.charAt(0).toUpperCase() : 'P';
    const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300" viewBox="0 0 400 300">
      <rect width="400" height="300" fill="#1A56E8"/>
      <text x="50%" y="50%" dominant-baseline="central" text-anchor="middle" font-family="sans-serif" font-size="120" font-weight="bold" fill="white" opacity="0.9">
        ${initial}
      </text>
    </svg>`;
    return `data:image/svg+xml;charset=utf-8,${encodeURIComponent(svg)}`;
  };

  if (!products || products.length === 0) {
    return (
      <div className="text-center py-24">
        <div className="w-20 h-20 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
            <svg className="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        <h3 className="text-xl font-bold text-slate-900 mb-2">Produk Tidak Ditemukan</h3>
        <p className="text-slate-500 mb-6">Coba kata kunci yang berbeda atau lihat semua produk kami.</p>
        <a href="/katalog" className="btn-primary">Lihat Semua Produk</a>
      </div>
    );
  }

  return (
    <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 place-items-center items-stretch">
      {products.map((product) => (
        <PlaceCard
          key={product.id}
          title={product.name}
          pricePerNight={formatPrice(product.base_price || 0)}
          images={product.image ? [`${apiUrl}${product.image}`] : [getFallbackImage(product.name)]}
          description={product.description || 'Produk berkualitas tinggi yang dapat disesuaikan dengan kebutuhan cetak Anda.'}
          rating={4.8}
          tags={[product.category_name || 'Printing']}
          dateRange="Custom Design"
          hostType="Premium"
          isTopRated={product.is_active}
          linkHref={`/produk/${product.id}`}
          className="h-full"
        />
      ))}
    </div>
  );
}

const rootElement = document.getElementById('catalog-grid-root');
if (rootElement) {
  try {
    const dataElement = document.getElementById('catalog-data');
    const products = dataElement ? JSON.parse(dataElement.textContent) : [];
    
    const apiUrlElement = document.getElementById('api-url');
    const apiUrl = apiUrlElement ? apiUrlElement.textContent.trim() : '';

    createRoot(rootElement).render(
      <React.StrictMode>
        <Toaster position="top-center" />
        <CatalogGrid products={products} apiUrl={apiUrl} />
      </React.StrictMode>
    );
  } catch (error) {
    console.error("Error rendering catalog grid:", error);
  }
}
