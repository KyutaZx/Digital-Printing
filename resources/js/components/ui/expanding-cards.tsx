"use client";

import * as React from "react";
import { cn } from "@/lib/utils"; 

export interface CardItem {
  id: string | number;
  title: string;
  description: string;
  imgSrc: string;
  icon: React.ReactNode;
  linkHref: string;
}

interface ExpandingCardsProps extends React.HTMLAttributes<HTMLUListElement> {
  items: CardItem[];
  defaultActiveIndex?: number;
}

export const ExpandingCards = React.forwardRef<
  HTMLUListElement,
  ExpandingCardsProps
>(({ className, items, defaultActiveIndex = 0, ...props }, ref) => {
  const [activeIndex, setActiveIndex] = React.useState<number | null>(
    defaultActiveIndex,
  );
  
  const [isDesktop, setIsDesktop] = React.useState(false);

  React.useEffect(() => {
    const handleResize = () => {
      setIsDesktop(window.innerWidth >= 768);
    };
    handleResize();
    window.addEventListener('resize', handleResize);
    return () => window.removeEventListener('resize', handleResize);
  }, []);

  const gridStyle = React.useMemo(() => {
    if (activeIndex === null) return {};
    
    if (isDesktop) {
      const columns = items
        .map((_, index) => (index === activeIndex ? "5fr" : "1fr"))
        .join(" ");
      return { gridTemplateColumns: columns };
    } else {
      const rows = items
        .map((_, index) => (index === activeIndex ? "4fr" : "1fr")) // Slightly lowered mobile active ratio for readability
        .join(" ");
      return { gridTemplateRows: rows };
    }
  }, [activeIndex, items.length, isDesktop]);

  const handleInteraction = (index: number) => {
    setActiveIndex(index);
  };

  const handleClick = (index: number, linkHref: string) => {
    if (activeIndex === index) {
      window.location.href = linkHref;
    } else {
      setActiveIndex(index);
    }
  };

  return (
    <ul
      className={cn(
        "w-full max-w-6xl gap-3",
        "grid",
        "h-[650px] md:h-[500px]",
        "transition-[grid-template-columns,grid-template-rows] duration-500 ease-out",
        className,
      )}
      style={{
        ...gridStyle,
        ...(isDesktop 
          ? { gridTemplateRows: '1fr' }
          : { gridTemplateColumns: '1fr' }
        )
      }}
      ref={ref}
      {...props}
    >
      {items.map((item, index) => (
        <li
          key={item.id}
          className={cn(
            "group relative cursor-pointer overflow-hidden rounded-2xl border border-slate-200/80 bg-card text-card-foreground shadow-sm transition-all duration-300",
            "md:min-w-[80px]",
            "min-h-0 min-w-0"
          )}
          onMouseEnter={() => handleInteraction(index)}
          onFocus={() => handleInteraction(index)}
          onClick={() => handleClick(index, item.linkHref)}
          tabIndex={0}
          data-active={activeIndex === index}
        >
          <img
            src={item.imgSrc}
            alt={item.title}
            className="absolute inset-0 h-full w-full object-cover transition-all duration-700 ease-out group-data-[active=true]:scale-100 group-data-[active=true]:grayscale-0 scale-110 grayscale"
          />
          <div className="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-900/40 to-transparent transition-opacity duration-300 group-data-[active=true]:opacity-100 opacity-60" />

          <article
            className="absolute inset-0 flex flex-col justify-end gap-2 p-5"
          >
            {/* Title shown vertically when collapsed on desktop */}
            <h3 className="hidden origin-left rotate-270 text-base font-bold uppercase tracking-wider text-white/95 whitespace-nowrap opacity-100 transition-all duration-300 ease-out md:block group-data-[active=true]:opacity-0 absolute left-8 bottom-6">
              {item.title}
            </h3>

            {/* Icon shown on active state */}
            <div className="text-primary-400 opacity-0 transform translate-y-2 transition-all duration-300 delay-75 ease-out group-data-[active=true]:opacity-100 group-data-[active=true]:translate-y-0">
              {item.icon}
            </div>

            {/* Title shown horizontally when active */}
            <h3 className="text-xl font-extrabold text-white opacity-0 transform translate-y-2 transition-all duration-300 delay-150 ease-out group-data-[active=true]:opacity-100 group-data-[active=true]:translate-y-0">
              {item.title}
            </h3>

            {/* Description shown when active */}
            <p className="w-full max-w-md text-sm text-slate-200/90 opacity-0 transform translate-y-2 transition-all duration-300 delay-225 ease-out group-data-[active=true]:opacity-100 group-data-[active=true]:translate-y-0 leading-relaxed">
              {item.description}
            </p>

            {/* CTA shown when active */}
            <div className="mt-1 opacity-0 transform translate-y-2 transition-all duration-300 delay-300 ease-out group-data-[active=true]:opacity-100 group-data-[active=true]:translate-y-0">
              <span className="inline-flex items-center gap-1 text-xs font-bold uppercase tracking-wider text-primary-400 group-hover:text-primary-300">
                Lihat Katalog
                <svg className="w-3.5 h-3.5 transform translate-x-0 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
              </span>
            </div>
          </article>
        </li>
      ))}
    </ul>
  );
});
ExpandingCards.displayName = "ExpandingCards";
