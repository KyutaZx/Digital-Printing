import React from 'react';
import { motion } from 'framer-motion';
import { cn } from '../../lib/utils';
import { AuroraBackground } from './AuroraBackground';
import { ShinyButton } from './shiny-button';
import { PointerHighlight } from './pointer-highlight';

export function PulseFitHero({
  logo = 'Jaya Mandiri',
  navigation = [],
  ctaButton,
  hideHeader = false,
  title,
  titleHighlight,
  subtitle,
  primaryAction,
  secondaryAction,
  disclaimer,
  socialProof,
  programs = [],
  className,
  children,
}) {
  return (
    <AuroraBackground
      className={cn(
        hideHeader ? 'min-h-[calc(100vh-4rem)] pt-28 sm:pt-32 lg:pt-36' : 'min-h-screen',
        className
      )}
      role="banner"
      aria-label="Hero section"
    >
      {!hideHeader && (
        <motion.header
          initial={{ opacity: 0, y: -20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.6 }}
          className="relative z-20 flex flex-row justify-between items-center px-8 lg:px-16 py-8"
        >
          <div className="font-bold text-2xl text-slate-900">{logo}</div>
          <nav className="hidden lg:flex flex-row items-center gap-8" aria-label="Main navigation">
            {navigation.map((item, index) => (
              <button
                key={index}
                type="button"
                onClick={item.onClick}
                className="flex flex-row items-center gap-1 text-base text-slate-600 hover:opacity-70 transition-opacity"
              >
                {item.label}
                {item.hasDropdown && (
                  <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden>
                    <path
                      d="M4 6L8 10L12 6"
                      stroke="currentColor"
                      strokeWidth="2"
                      strokeLinecap="round"
                      strokeLinejoin="round"
                    />
                  </svg>
                )}
              </button>
            ))}
          </nav>
          {ctaButton && (
            <button
              type="button"
              onClick={ctaButton.onClick}
              className="px-6 py-3 rounded-full bg-white border border-slate-200 text-base font-medium text-slate-900 shadow-sm hover:scale-105 transition-all"
            >
              {ctaButton.label}
            </button>
          )}
        </motion.header>
      )}

      {children ? (
        <div className="relative z-10 flex-1 flex items-center justify-center w-full">{children}</div>
      ) : (
        <div className="relative z-10 flex-1 flex flex-col items-center justify-center px-4 pb-8 pt-4 sm:pt-6">
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.8, delay: 0.2 }}
            className="flex flex-col items-center text-center max-w-4xl gap-8"
          >
            <h1 className="font-extrabold text-slate-900 tracking-tight text-[clamp(2.25rem,6vw,4.5rem)] leading-[1.1]">
              {title}
              {titleHighlight && (
                <>
                  <br />
                  <PointerHighlight>
                    <span className="text-primary-600 px-3 inline-block">{titleHighlight}</span>
                  </PointerHighlight>
                </>
              )}
            </h1>

            <p className="text-slate-600 text-[clamp(1rem,2vw,1.25rem)] leading-relaxed max-w-xl">
              {subtitle}
            </p>

            {(primaryAction || secondaryAction) && (
              <motion.div
                initial={{ opacity: 0, scale: 0.95 }}
                animate={{ opacity: 1, scale: 1 }}
                transition={{ duration: 0.6, delay: 0.4 }}
                className="flex flex-col sm:flex-row items-center gap-4"
              >
                {primaryAction && (
                  <ShinyButton
                    onClick={primaryAction.onClick}
                    className="flex flex-row items-center gap-2"
                  >
                    {primaryAction.label}
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden className="w-5 h-5">
                      <path
                        d="M7 10H13M13 10L10 7M13 10L10 13"
                        stroke="currentColor"
                        strokeWidth="2"
                        strokeLinecap="round"
                        strokeLinejoin="round"
                      />
                    </svg>
                  </ShinyButton>
                )}
                {secondaryAction && (
                  <button
                    type="button"
                    onClick={secondaryAction.onClick}
                    className="relative overflow-hidden before:absolute before:inset-0 before:rounded-[inherit] before:bg-[linear-gradient(45deg,transparent_25%,rgba(255,255,255,0.4)_50%,transparent_75%,transparent_100%)] before:bg-[length:250%_250%,100%_100%] before:bg-[position:200%_0,0_0] before:bg-no-repeat before:transition-[background-position_0s_ease] before:duration-1000 hover:before:bg-[position:-100%_0,0_0] hover:before:transition-[background-position_1s_ease] px-8 py-4 rounded-full border border-primary-500 bg-primary-600 text-white text-lg font-medium hover:scale-105 transition-all shadow-md"
                  >
                    {secondaryAction.label}
                  </button>
                )}
              </motion.div>
            )}

            {disclaimer && (
              <motion.p
                initial={{ opacity: 0 }}
                animate={{ opacity: 1 }}
                transition={{ duration: 0.6, delay: 0.6 }}
                className="text-sm text-slate-500 italic"
              >
                {disclaimer}
              </motion.p>
            )}

            {socialProof && (
              <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.6, delay: 0.7 }}
                className="flex flex-row items-center gap-3"
              >
                <div className="flex flex-row -space-x-2">
                  {socialProof.avatars.map((avatar, index) => (
                    <img
                      key={index}
                      src={avatar}
                      alt=""
                      className="w-10 h-10 rounded-full border-2 border-white object-cover"
                    />
                  ))}
                </div>
                <span className="text-sm font-medium text-slate-600">{socialProof.text}</span>
              </motion.div>
            )}
          </motion.div>
        </div>
      )}

      {programs.length > 0 && (
        <motion.div
          initial={{ opacity: 0, y: 100 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 1, delay: 0.8 }}
          className="relative z-10 w-full overflow-hidden py-12 lg:py-16"
        >
          <div
            className="absolute left-0 top-0 bottom-0 z-10 w-[120px] pointer-events-none"
            style={{
              background: 'linear-gradient(90deg, rgb(250 250 250) 0%, rgba(250, 250, 250, 0) 100%)',
            }}
          />
          <div
            className="absolute right-0 top-0 bottom-0 z-10 w-[120px] pointer-events-none"
            style={{
              background: 'linear-gradient(270deg, rgb(250 250 250) 0%, rgba(250, 250, 250, 0) 100%)',
            }}
          />

          <motion.div
            className="flex items-center gap-6 pl-6"
            animate={{
              x: [0, -((programs.length * 380) / 2)],
            }}
            transition={{
              x: {
                repeat: Infinity,
                repeatType: 'loop',
                duration: Math.max(programs.length * 4, 12),
                ease: 'linear',
              },
            }}
          >
            {[...programs, ...programs].map((program, index) => (
              <motion.button
                key={`${program.title}-${index}`}
                type="button"
                whileHover={{ scale: 1.05, y: -10 }}
                transition={{ duration: 0.3 }}
                onClick={program.onClick}
                className="flex-shrink-0 cursor-pointer relative overflow-hidden w-[320px] sm:w-[356px] h-[420px] sm:h-[480px] rounded-3xl shadow-xl text-left"
              >
                <img
                  src={program.image}
                  alt={program.title}
                  className="w-full h-full object-cover"
                />
                <div
                  className="absolute inset-0"
                  style={{
                    background:
                      'linear-gradient(180deg, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 0.75) 100%)',
                  }}
                />
                <div className="absolute bottom-0 left-0 right-0 p-6 flex flex-col gap-2">
                  <span className="text-xs font-medium text-white/80 uppercase tracking-widest">
                    {program.category}
                  </span>
                  <h3 className="text-xl sm:text-2xl font-semibold text-white leading-snug">
                    {program.title}
                  </h3>
                </div>
              </motion.button>
            ))}
          </motion.div>
        </motion.div>
      )}
    </AuroraBackground>
  );
}
