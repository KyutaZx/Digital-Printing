import { create } from 'zustand';
import { persist } from 'zustand/middleware';

export const useOrderStore = create(
  persist(
    (set, get) => ({
      orders: [],
      addOrder: (order) => set((state) => ({
        orders: [order, ...state.orders]
      })),
      updateOrderStatus: (orderId, newStatus) => set((state) => ({
        orders: state.orders.map((order) => 
          order.id === orderId ? { ...order, status: newStatus } : order
        )
      })),
      updateOrder: (orderId, updates) => set((state) => ({
        orders: state.orders.map((order) =>
          order.id === orderId ? { ...order, ...updates } : order
        )
      })),
      getOrderById: (orderId) => {
        return get().orders.find((o) => o.id === orderId);
      }
    }),
    {
      name: 'order-storage',
    }
  )
);
