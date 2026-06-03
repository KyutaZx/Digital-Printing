"use client";

import React, { useState, useRef, useEffect } from 'react';
import { 
  motion, 
  useMotionValue, 
  useMotionTemplate, 
  useAnimationFrame 
} from "framer-motion";
import { MousePointerClick, Info, Sun, Moon, Settings2 } from 'lucide-react';
import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

/**
 * Standard Shadcn utility for merging Tailwind classes safely.
 */
function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs));
}

/**
 * Helper component for the SVG grid pattern.
 */
const GridPattern = ({ offsetX, offsetY, size }: { offsetX: any; offsetY: any; size: number }) => {
  return (
    <svg className="w-full h-full">
      <defs>
        <motion.pattern
          id="grid-pattern-infinite"
          width={size}
          height={size}
          patternUnits="userSpaceOnUse"
          x={offsetX}
          y={offsetY}
        >
          <path
            d={`M ${size} 0 L 0 0 0 ${size}`}
            fill="none"
            stroke="currentColor"
            strokeWidth="1"
            className="text-slate-300 dark:text-slate-700" 
          />
        </motion.pattern>
      </defs>
      <rect width="100%" height="100%" fill="url(#grid-pattern-infinite)" />
    </svg>
  );
};

/**
 * The Infinite Grid Component
 * Displays a scrolling background grid that reveals an active layer on mouse hover.
 */
export const InfiniteGrid = ({ className, size = 40, spotlightColor = "primary" }: { className?: string; size?: number; spotlightColor?: string }) => {
  const containerRef = useRef<HTMLDivElement>(null);

  // Track mouse position with Motion Values for performance
  const mouseX = useMotionValue(0);
  const mouseY = useMotionValue(0);

  useEffect(() => {
    const handleGlobalMouseMove = (e: MouseEvent) => {
      if (!containerRef.current) return;
      const rect = containerRef.current.getBoundingClientRect();
      mouseX.set(e.clientX - rect.left);
      mouseY.set(e.clientY - rect.top);
    };
    window.addEventListener('mousemove', handleGlobalMouseMove);
    return () => window.removeEventListener('mousemove', handleGlobalMouseMove);
  }, [mouseX, mouseY]);

  // Create a dynamic radial mask for the "flashlight" effect
  const maskImage = useMotionTemplate`radial-gradient(280px circle at ${mouseX}px ${mouseY}px, black, transparent)`;

  return (
    <div
      ref={containerRef}
      className={cn(
        "fixed inset-0 w-full h-full overflow-hidden bg-transparent pointer-events-none z-0",
        className
      )}
      style={{
        maskImage: 'linear-gradient(to bottom, transparent, black 200px, black calc(100% - 200px), transparent)',
        WebkitMaskImage: 'linear-gradient(to bottom, transparent, black 200px, black calc(100% - 200px), transparent)'
      }}
    >
      <style dangerouslySetInnerHTML={{ __html: `
        @keyframes infinite-grid-scroll-css {
          from {
            background-position: 0 0;
          }
          to {
            background-position: ${size}px ${size}px;
          }
        }
        @keyframes hue-shift {
          0% { filter: hue-rotate(0deg); opacity: 0.3; }
          50% { filter: hue-rotate(180deg); opacity: 0.6; }
          100% { filter: hue-rotate(360deg); opacity: 0.3; }
        }
        .infinite-grid-bg-base-css {
          background-image: 
            linear-gradient(to right, rgba(59, 130, 246, 0.4) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(59, 130, 246, 0.4) 1px, transparent 1px);
          background-size: ${size}px ${size}px;
          animation: infinite-grid-scroll-css 15s linear infinite, hue-shift 10s alternate infinite;
        }
        .infinite-grid-bg-highlight-primary-css {
          background-image: 
            linear-gradient(to right, rgba(59, 130, 246, 0.3) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(59, 130, 246, 0.3) 1px, transparent 1px);
          background-size: ${size}px ${size}px;
          animation: infinite-grid-scroll-css 15s linear infinite;
        }
        .infinite-grid-bg-highlight-emerald-css {
          background-image: 
            linear-gradient(to right, rgba(16, 185, 129, 0.3) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(16, 185, 129, 0.3) 1px, transparent 1px);
          background-size: ${size}px ${size}px;
          animation: infinite-grid-scroll-css 15s linear infinite;
        }
      `}} />

      {/* Layer 1: Subtle background grid (always visible) */}
      <div className="absolute inset-0 z-0 infinite-grid-bg-base-css" />

      {/* Layer 2: Highlighted grid (revealed by mouse mask) */}
      <motion.div 
        className={cn(
          "absolute inset-0 z-0",
          spotlightColor === "primary" ? "infinite-grid-bg-highlight-primary-css" : "infinite-grid-bg-highlight-emerald-css"
        )}
        style={{ maskImage, WebkitMaskImage: maskImage }}
      />

      {/* Decorative Blur Spheres */}
      <div className="absolute inset-0 pointer-events-none z-0">
        <div className="absolute right-[5%] top-[10%] w-[30%] h-[30%] rounded-full bg-primary-500/5 blur-[120px]" />
        <div className="absolute left-[5%] bottom-[10%] w-[30%] h-[30%] rounded-full bg-emerald-500/5 blur-[120px]" />
      </div>
    </div>
  );
};

const App: React.FC = () => {
  const [count, setCount] = useState(0);
  const [gridSize, setGridSize] = useState(40);
  const [isDark, setIsDark] = useState(false);

  useEffect(() => {
    if (isDark) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
  }, [isDark]);

  return (
    <div className="w-full relative min-h-screen bg-background">
      <button
        onClick={() => setIsDark(!isDark)}
        className="fixed top-4 right-4 z-50 p-3 rounded-full bg-background/50 backdrop-blur-sm border border-border shadow-lg hover:scale-110 active:scale-95 transition-all flex items-center justify-center group"
        aria-label="Toggle Theme"
      >
        {isDark ? (
          <Sun className="w-5 h-5 text-yellow-500 group-hover:rotate-45 transition-transform" />
        ) : (
          <Moon className="w-5 h-5 text-indigo-500 group-hover:-rotate-12 transition-transform" />
        )}
      </button>

      <InfiniteGrid size={gridSize} />

      <div className="absolute bottom-10 right-10 z-30 pointer-events-auto">
        <div className="bg-background/80 backdrop-blur-md border border-border p-4 rounded-xl shadow-2xl space-y-3 min-w-[200px]">
          <div className="flex items-center gap-2 text-sm font-medium text-foreground">
            <Settings2 className="w-4 h-4" />
            Grid Density
          </div>
          <input 
            type="range" 
            min="20" 
            max="100" 
            value={gridSize} 
            onChange={(e) => setGridSize(Number(e.target.value))}
            className="w-full h-1.5 bg-secondary rounded-lg appearance-none cursor-pointer accent-primary"
          />
          <div className="flex justify-between text-[10px] text-muted-foreground uppercase tracking-widest font-mono">
            <span>Dense</span>
            <span>Sparse ({gridSize}px)</span>
          </div>
        </div>
      </div>

      <div className="relative z-10 flex min-h-screen flex-col items-center justify-center text-center px-4 max-w-3xl mx-auto space-y-6 pointer-events-none">
         <div className="space-y-2">
          <h1 className="text-4xl md:text-6xl font-semibold tracking-tight text-foreground drop-shadow-sm">
            The Infinite Grid
          </h1>
          <p className="text-lg md:text-xl font-semibold text-muted-foreground">
            Move your cursor to reveal the active grid layer. <br/>
            The pattern scrolls infinitely in the background.
          </p>
        </div>
        
        <div className="flex gap-4 pointer-events-auto">
          <motion.button 
              onClick={() => setCount(count + 1)}
              whileHover={{ 
                scale: 1.05, 
                y: -4,
                backgroundColor: "#4338ca",
                borderColor: "#6366f1",
                color: "#ffffff",
                boxShadow: "0 25px 50px -12px rgba(67, 56, 202, 0.6)"
              }}
              whileTap={{ scale: 0.98, y: 0 }}
              transition={{ type: "spring", stiffness: 400, damping: 15 }}
              className="flex items-center gap-2 px-8 py-3 bg-primary text-primary-foreground font-semibold rounded-md shadow-md border-2 border-transparent transition-colors"
          >
              <MousePointerClick className="w-4 h-4" />
              Interact ({count})
          </motion.button>
          
          <motion.button 
              whileHover={{ 
                scale: 1.05, 
                y: -4, 
                backgroundColor: "#6d28d9",
                borderColor: "#8b5cf6",
                color: "#ffffff",
                boxShadow: "0 25px 50px -12px rgba(109, 40, 217, 0.6)"
              }}
              whileTap={{ scale: 0.98, y: 0 }}
              transition={{ type: "spring", stiffness: 400, damping: 15 }}
              className="flex items-center gap-2 px-8 py-3 bg-secondary text-secondary-foreground font-semibold rounded-md border-2 border-transparent transition-colors"
          >
              <Info className="w-4 h-4" />
              Learn More
          </motion.button>
        </div>
      </div>

      <footer className="fixed bottom-4 left-4 z-50 text-[10px] uppercase tracking-widest text-muted-foreground opacity-50 font-mono">
        Shadcn Infinite Grid v1.1
      </footer>
    </div>
  );
};

export default App;
