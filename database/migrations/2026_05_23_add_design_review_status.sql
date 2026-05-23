-- LANGKAH 1: Jalankan ini dulu, lalu tekan F5 (Execute). Tunggu sukses di tab Messages.
ALTER TYPE public.status_order ADD VALUE IF NOT EXISTS 'design_review';

-- LANGKAH 2: Buka Query Tool baru ATAU jalankan baris di bawah ini TERPISAH (query baru, F5 lagi).
-- Jangan jalankan kedua perintah sekaligus dalam satu Execute — PostgreSQL error 55P04.
-- UPDATE public.orders SET status = 'design_review' WHERE status = 'paid';
