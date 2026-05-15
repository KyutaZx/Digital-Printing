import { create } from 'zustand';
import { persist } from 'zustand/middleware';
import mockProducts from '../mock/products.json';

export const useProductStore = create(
  persist(
    (set, get) => ({
      products: mockProducts, // Initialize with mock JSON if empty
      
      addProduct: (product) => {
        set((state) => ({
          products: [{ id: `prod-${Date.now()}`, ...product }, ...state.products]
        }));
      },
      
      updateProduct: (id, updatedData) => {
        set((state) => ({
          products: state.products.map(p => p.id === id ? { ...p, ...updatedData } : p)
        }));
      },
      
      deleteProduct: (id) => {
        set((state) => ({
          products: state.products.filter(p => p.id !== id)
        }));
      },
      
      getProductById: (id) => {
        return get().products.find(p => p.id === id);
      }
    }),
    {
      name: 'jaya-products-storage',
    }
  )
);
