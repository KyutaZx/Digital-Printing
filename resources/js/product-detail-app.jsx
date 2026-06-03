import React, { useState, useEffect } from 'react';
import { createRoot } from 'react-dom/client';
import { motion, AnimatePresence, useReducedMotion } from 'framer-motion';
import { 
  Heart, ShoppingCart, Star, ChevronRight, Share2, Camera,
  ArrowLeft, ChevronLeft, Send, Tag, Ruler, Users, Info, Plus, Minus
} from 'lucide-react';
import { cn } from '@/lib/utils';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Card } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import { ScrollArea } from '@/components/ui/scroll-area';
import { AspectRatio } from '@/components/ui/aspect-ratio';
import { Textarea } from '@/components/ui/textarea';
import { Label } from '@/components/ui/label';

const StarRating = ({ rating, className }) => (
  <div className={cn("flex items-center gap-0.5", className)}>
    {[...Array(5)].map((_, i) => (
      <Star
        key={i}
        className={cn(
          "h-4 w-4",
          i < Math.floor(rating) ? "text-yellow-400 fill-yellow-400" : "text-muted-foreground/50"
        )}
      />
    ))}
    <span className="ml-2 text-sm font-medium text-muted-foreground">{rating.toFixed(1)}</span>
  </div>
);

function ProductPage({ product, apiUrl, csrfToken, isLoggedIn }) {
  // Setup images
  const images = (product.images && product.images.length > 0) ? product.images : (product.image ? [product.image] : []);
  const formattedImages = images.length > 0 
    ? images.map((img, idx) => ({ src: `${apiUrl}${img}`, alt: `${product.name} ${idx + 1}` }))
    : [{ src: 'https://placehold.co/800x800?text=No+Image', alt: 'No image' }];

  const categoryName = product.category_name || 'Kategori';
  const isCustomSize = ['Banner Outdoor', 'Spanduk', 'Sticker Custom'].includes(categoryName);

  const [currentImageIndex, setCurrentImageIndex] = useState(0);
  const [selectedVariant, setSelectedVariant] = useState(
    product.variants && product.variants.length > 0 ? product.variants[0].id.toString() : ""
  );
  const [quantity, setQuantity] = useState(1);
  const [notes, setNotes] = useState("");
  
  // Custom sizing states
  const [width, setWidth] = useState(1);
  const [height, setHeight] = useState(1);
  const [selectedMaterial, setSelectedMaterial] = useState("Standar");

  const prev = () => setCurrentImageIndex((prev) => (prev - 1 + formattedImages.length) % formattedImages.length);
  const next = () => setCurrentImageIndex((prev) => (prev + 1) % formattedImages.length);

  // Price calculations
  let currentPrice = product.base_price || 0;
  if (selectedVariant && product.variants) {
    const v = product.variants.find(v => v.id.toString() === selectedVariant);
    if (v) currentPrice = v.price;
  }
  
  let area = 1;
  if (isCustomSize) {
    area = width * height;
    let materialMultiplier = 1;
    if (selectedMaterial.includes('Korea')) materialMultiplier = 1.5;
    if (selectedMaterial.includes('Vinyl')) materialMultiplier = 1.8;
    currentPrice = currentPrice * area * materialMultiplier;
  }

  const totalPrice = currentPrice * quantity;

  const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0
    }).format(price);
  };

  const containerVariants = {
    hidden: { opacity: 0, y: 20 },
    visible: { opacity: 1, y: 0, transition: { duration: 0.6, staggerChildren: 0.1 } },
  };

  const itemVariants = {
    hidden: { opacity: 0, y: 20 },
    visible: { opacity: 1, y: 0, transition: { duration: 0.4 } },
  };

  return (
    <div className="w-full min-h-screen bg-slate-50 pt-20 pb-24">
      <div className="container mx-auto max-w-7xl px-4 py-8">
        {/* Breadcrumbs */}
        <nav aria-label="Breadcrumb" className="flex items-center text-sm text-slate-500 mb-6">
          <a href="/" className="hover:text-primary-600 transition-colors">Beranda</a>
          <ChevronRight className="h-4 w-4 mx-1" />
          <a href="/katalog" className="hover:text-primary-600 transition-colors">Katalog</a>
          <ChevronRight className="h-4 w-4 mx-1" />
          <span className="text-slate-900">{product.name}</span>
        </nav>

        <form method="POST" action="/cart/add" className="contents">
          <input type="hidden" name="_token" value={csrfToken} />
          <input type="hidden" name="product_id" value={product.id} />
          <input type="hidden" name="variant_id" value={selectedVariant || 0} />
          <input type="hidden" name="quantity" value={quantity} />
          <input type="hidden" name="notes" value={notes} />
          {isCustomSize && (
            <>
              <input type="hidden" name="material" value={selectedMaterial} />
              <input type="hidden" name="dimensions" value={`${width}m x ${height}m`} />
            </>
          )}

          <motion.div variants={containerVariants} initial="hidden" animate="visible" className="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
            
            {/* Left: Image Gallery */}
            <motion.div variants={itemVariants} className="space-y-4">
              <Card className="relative overflow-hidden rounded-3xl border-slate-200 bg-white p-4 shadow-sm">
                <div className="flex items-center justify-between mb-4">
                  <a href="/katalog">
                    <Button type="button" variant="outline" size="icon" className="rounded-full">
                      <ArrowLeft className="h-4 w-4" />
                    </Button>
                  </a>
                </div>

                <div className="grid grid-cols-12 gap-4">
                  {/* Thumbnails */}
                  <div className="col-span-3">
                    <ScrollArea className="h-[420px]">
                      <div className="flex flex-col gap-3">
                        {formattedImages.map((img, i) => (
                          <button
                            key={i}
                            type="button"
                            onClick={() => setCurrentImageIndex(i)}
                            className={cn(
                              "relative overflow-hidden rounded-xl border p-0 transition-all bg-slate-50",
                              i === currentImageIndex ? "border-primary-600 ring-2 ring-primary-600/20" : "border-slate-200 hover:border-primary-600/50"
                            )}
                          >
                            <img src={img.src} alt={img.alt} className="h-16 w-full object-cover" />
                          </button>
                        ))}
                      </div>
                    </ScrollArea>
                  </div>

                  {/* Main Image */}
                  <div className="col-span-9">
                    <div className="relative">
                      <AspectRatio ratio={4 / 5}>
                        <AnimatePresence mode="wait">
                          <motion.div
                            key={currentImageIndex}
                            initial={{ opacity: 0 }}
                            animate={{ opacity: 1 }}
                            exit={{ opacity: 0 }}
                            transition={{ duration: 0.3 }}
                            className="h-full w-full overflow-hidden rounded-2xl bg-slate-100 flex items-center justify-center"
                          >
                            <img src={formattedImages[currentImageIndex].src} alt={formattedImages[currentImageIndex].alt} className="w-full h-full object-contain" />
                          </motion.div>
                        </AnimatePresence>
                      </AspectRatio>

                      {/* Navigation Controls */}
                      {formattedImages.length > 1 && (
                        <div className="absolute bottom-3 right-3 flex gap-2">
                          <Button type="button" size="icon" variant="secondary" className="rounded-full bg-white/80 backdrop-blur shadow-sm" onClick={prev}>
                            <ChevronLeft className="h-4 w-4" />
                          </Button>
                          <Button type="button" size="icon" variant="secondary" className="rounded-full bg-white/80 backdrop-blur shadow-sm" onClick={next}>
                            <ChevronRight className="h-4 w-4" />
                          </Button>
                        </div>
                      )}
                    </div>
                  </div>
                </div>

                {formattedImages.length > 1 && (
                  <div className="flex justify-center items-center mt-4">
                    <div className="flex gap-2">
                      {formattedImages.map((_, index) => (
                        <button
                          key={index}
                          type="button"
                          onClick={() => setCurrentImageIndex(index)}
                          className={cn(
                            "h-2 w-2 rounded-full transition-colors",
                            currentImageIndex === index ? "bg-primary-600" : "bg-slate-300"
                          )}
                        />
                      ))}
                    </div>
                  </div>
                )}
              </Card>
            </motion.div>

            {/* Right: Product Details */}
            <motion.div variants={itemVariants} className="space-y-6">
              <div>
                <p className="text-sm text-slate-500 uppercase tracking-wider mb-2 font-semibold">
                  {categoryName}
                </p>
                <h1 className="text-4xl font-bold tracking-tight text-slate-900 mb-4">{product.name}</h1>
                
                <div className="flex items-center gap-4 mb-6">

                  {product.is_active !== false ? (
                    <Badge variant="secondary" className="bg-green-100 text-green-700 hover:bg-green-100">✓ Tersedia</Badge>
                  ) : (
                    <Badge variant="destructive">Tidak Tersedia</Badge>
                  )}
                </div>

                <div className="flex items-center gap-3 mb-6">
                  <span className="text-4xl font-black text-primary-600">
                    {formatPrice(currentPrice)}
                  </span>
                  {isCustomSize && (
                    <span className="text-lg text-slate-500 font-medium">/ pcs</span>
                  )}
                </div>
              </div>

              {/* Variant Selection */}
              {product.variants && product.variants.length > 0 && (
                <div>
                  <Label className="text-base font-semibold mb-3 block text-slate-900">Pilih Varian / Ukuran</Label>
                  <div className="flex flex-wrap gap-2">
                    {product.variants.map((variant) => (
                      <button
                        key={variant.id}
                        type="button"
                        onClick={() => setSelectedVariant(variant.id.toString())}
                        className={cn(
                          "px-4 py-2 rounded-lg border-2 transition-all flex flex-col items-start",
                          selectedVariant === variant.id.toString()
                            ? "border-primary-600 bg-primary-50 text-primary-700"
                            : "border-slate-200 hover:border-primary-600/50 bg-white"
                        )}
                      >
                        <span className="font-semibold">{variant.variant_name}</span>
                        <span className="text-xs opacity-80">+ Rp {variant.price.toLocaleString('id-ID')}</span>
                      </button>
                    ))}
                  </div>
                </div>
              )}

              {/* Custom Dimensions (If applicable) */}
              {isCustomSize && (
                <div className="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                  <div>
                    <Label className="text-sm font-semibold mb-2 block text-slate-700">Pilih Material</Label>
                    <select value={selectedMaterial} onChange={e => setSelectedMaterial(e.target.value)} className="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-primary-500">
                      <option value="Standar">Standar</option>
                      <option value="Flexi Korea 440gr">Flexi Korea 440gr</option>
                      <option value="Vinyl Transparan">Vinyl Transparan</option>
                    </select>
                  </div>
                  <div className="grid grid-cols-2 gap-4">
                    <div>
                      <Label className="text-sm font-semibold mb-2 block text-slate-700">Lebar (Meter)</Label>
                      <input type="number" min="0.5" step="0.5" value={width} onChange={e => setWidth(parseFloat(e.target.value) || 1)} className="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-primary-500" />
                    </div>
                    <div>
                      <Label className="text-sm font-semibold mb-2 block text-slate-700">Tinggi (Meter)</Label>
                      <input type="number" min="0.5" step="0.5" value={height} onChange={e => setHeight(parseFloat(e.target.value) || 1)} className="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-primary-500" />
                    </div>
                  </div>
                  <div className="text-sm font-medium text-slate-600 flex items-center gap-2 bg-slate-50 p-3 rounded-lg border border-slate-100">
                    <Ruler className="w-4 h-4 text-primary-500" /> Total Luas: <span className="font-bold text-primary-600">{area} m²</span>
                  </div>
                </div>
              )}

              {/* Quantity Selection */}
              <div>
                <Label className="text-base font-semibold mb-3 block text-slate-900">Jumlah</Label>
                <div className="flex items-center gap-3">
                  <Button
                    type="button"
                    variant="outline"
                    size="icon"
                    className="h-12 w-12 rounded-xl"
                    onClick={() => setQuantity(Math.max(1, quantity - 1))}
                    disabled={quantity <= 1}
                  >
                    <Minus className="h-4 w-4" />
                  </Button>
                  <span className="text-2xl font-semibold w-16 text-center text-slate-900">{quantity}</span>
                  <Button
                    type="button"
                    variant="outline"
                    size="icon"
                    className="h-12 w-12 rounded-xl"
                    onClick={() => setQuantity(quantity + 1)}
                  >
                    <Plus className="h-4 w-4" />
                  </Button>
                </div>
              </div>

              {/* Notes */}
              <div>
                <Label htmlFor="notes" className="text-base font-semibold mb-3 block text-slate-900">Catatan (Opsional)</Label>
                <Textarea
                  id="notes"
                  placeholder="Misal: Finishing mata ayam, atau ukuran khusus..."
                  value={notes}
                  onChange={(e) => setNotes(e.target.value)}
                  className="min-h-[100px] resize-none bg-white border-slate-200 focus:ring-primary-500"
                />
              </div>

              {/* Total & Action Buttons */}
              <div className="pt-6 border-t border-slate-200">
                <div className="flex items-center justify-between mb-6">
                  <span className="text-slate-500 font-bold uppercase tracking-wide">Total Bayar</span>
                  <span className="text-3xl font-black text-slate-900">{formatPrice(totalPrice)}</span>
                </div>

                <div className="flex flex-col sm:flex-row gap-3">
                  {isLoggedIn ? (
                    <>
                      <Button type="submit" name="action" value="cart" size="lg" variant="outline" className="flex-1 gap-2 h-14 rounded-xl border-2 border-primary-600 text-primary-600 hover:bg-primary-50 text-base font-bold">
                        <ShoppingCart className="h-5 w-5" />
                        Tambah Keranjang
                      </Button>
                      <Button type="submit" formAction="/pesanan/beli-sekarang" name="action" value="buy" size="lg" className="flex-1 gap-2 h-14 rounded-xl bg-primary-600 hover:bg-primary-700 text-white shadow-lg shadow-primary-600/30 text-base font-bold">
                        Beli Sekarang
                      </Button>
                    </>
                  ) : (
                    <Button asChild size="lg" className="w-full h-14 rounded-xl bg-primary-600 hover:bg-primary-700 text-white shadow-lg shadow-primary-600/30 text-base font-bold">
                      <a href="/login">Masuk untuk Memesan</a>
                    </Button>
                  )}
                </div>
              </div>

              {/* Description */}
              <div className="pt-6 border-t border-slate-200 mt-8">
                <h3 className="font-semibold text-lg mb-3 text-slate-900">Deskripsi Produk</h3>
                <div className="text-slate-600 leading-relaxed text-sm whitespace-pre-line">
                  {product.description || "Tidak ada deskripsi untuk produk ini."}
                </div>
              </div>

            </motion.div>
          </motion.div>
        </form>
      </div>
    </div>
  );
}

const rootElement = document.getElementById('product-detail-root');
if (rootElement) {
  try {
    const dataElement = document.getElementById('product-data');
    const product = dataElement ? JSON.parse(dataElement.textContent) : null;
    
    const apiUrlElement = document.getElementById('api-url');
    const apiUrl = apiUrlElement ? apiUrlElement.textContent.trim() : '';

    const csrfElement = document.getElementById('csrf-token');
    const csrfToken = csrfElement ? csrfElement.textContent.trim() : '';

    const loggedInElement = document.getElementById('is-logged-in');
    const isLoggedIn = loggedInElement ? loggedInElement.textContent.trim() === 'true' : false;

    if (product) {
      createRoot(rootElement).render(
        <React.StrictMode>
          <ProductPage product={product} apiUrl={apiUrl} csrfToken={csrfToken} isLoggedIn={isLoggedIn} />
        </React.StrictMode>
      );
    }
  } catch (error) {
    console.error("Error rendering product detail:", error);
  }
}
