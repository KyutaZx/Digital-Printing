import { create } from 'zustand';
import { persist } from 'zustand/middleware';

export const useCartStore = create(
  persist(
    (set, get) => ({
      items: [],
      addItem: (item) => set((state) => {
        // Check if item already exists based on productId and options
        const existingItemIndex = state.items.findIndex(
          (i) => i.productId === item.productId && JSON.stringify(i.options) === JSON.stringify(item.options)
        );

        if (existingItemIndex >= 0) {
          const newItems = [...state.items];
          newItems[existingItemIndex].quantity += item.quantity;
          newItems[existingItemIndex].subtotal = newItems[existingItemIndex].quantity * newItems[existingItemIndex].price;
          return { items: newItems };
        }

        // New item
        return { items: [...state.items, { ...item, id: crypto.randomUUID(), subtotal: item.quantity * item.price }] };
      }),
      removeItem: (id) => set((state) => ({
        items: state.items.filter((item) => item.id !== id)
      })),
      updateQuantity: (id, quantity) => set((state) => ({
        items: state.items.map((item) => {
          if (item.id === id) {
            return { ...item, quantity, subtotal: quantity * item.price };
          }
          return item;
        })
      })),
      clearCart: () => set({ items: [] }),
      getCartTotal: () => get().items.reduce((total, item) => total + item.subtotal, 0),
    }),
    {
      name: 'cart-storage',
    }
  )
);
