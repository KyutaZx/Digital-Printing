import React from 'react';
import { motion } from 'framer-motion';
import { cn } from '@/lib/utils';
import { BorderRotate } from './animated-gradient-border';

const LinkCard = React.forwardRef(
  ({ className, title, description, imageUrl, href, stepNum, ...props }, ref) => {
    const cardVariants = {
      initial: { scale: 1, y: 0 },
      hover: {
        scale: 1.03,
        y: -5,
        transition: {
          type: 'spring',
          stiffness: 300,
          damping: 15,
        },
      },
    };

    return (
      <motion.div
        ref={ref}
        variants={cardVariants}
        initial="initial"
        whileHover="hover"
        className="w-full h-full"
      >
        <BorderRotate
          animationMode="rotate-on-hover"
          animationSpeed={3}
          backgroundColor="#ffffff"
          gradientColors={{
            primary: '#3b82f6',   // blue-500
            secondary: '#10b981', // emerald-500
            accent: '#06b6d4'     // cyan-500
          }}
          borderWidth={2}
          borderRadius={24}
          className={cn(
            'group relative flex h-[400px] w-full flex-col justify-between overflow-hidden',
            'p-6 md:p-8 text-slate-800 shadow-md',
            'focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2',
            className
          )}
          {...props}
        >
        {/* Step Number Badge */}
        <div className="absolute top-6 right-6 z-20">
          <span className="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-50 text-blue-600 font-display font-bold text-base shadow-sm">
            {stepNum}
          </span>
        </div>

        {/* Text content */}
        <div className="z-10 relative">
          <h3 className="mb-3 font-display text-2xl font-bold tracking-tight text-slate-900">
            {title}
          </h3>
          <p className="max-w-[85%] text-sm text-slate-500 leading-relaxed">
            {description}
          </p>
        </div>

        {/* Image container with a subtle scale effect on hover */}
        <div className="absolute bottom-0 right-0 h-56 w-56 translate-x-4 translate-y-4 transform">
          <motion.img
            src={imageUrl}
            alt={`${title} illustration`}
            className="h-full w-full object-contain transition-transform duration-500 ease-out group-hover:scale-110 drop-shadow-xl"
          />
        </div>
        </BorderRotate>
      </motion.div>
    );
  }
);

LinkCard.displayName = 'LinkCard';

export { LinkCard };
