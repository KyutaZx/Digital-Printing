import * as React from "react";
import { motion } from "framer-motion";
import { Button } from "@/components/ui/button";

interface ActionProps {
  text: string;
  onClick: () => void;
  icon?: React.ReactNode;
}

interface ContactStateProps {
  imageUrl: string;
  title: string;
  description: string;
  primaryAction: ActionProps;
  secondaryAction: ActionProps;
}

export const ContactState = ({
  imageUrl,
  title,
  description,
  primaryAction,
  secondaryAction,
}: ContactStateProps) => {
  const containerVariants = {
    hidden: { opacity: 0 },
    visible: {
      opacity: 1,
      transition: {
        staggerChildren: 0.1,
      },
    },
  };

  const itemVariants = {
    hidden: { y: 20, opacity: 0 },
    visible: {
      y: 0,
      opacity: 1,
      transition: {
        type: "spring",
        stiffness: 100,
        damping: 12,
      },
    },
  };

  return (
    <motion.div
      className="group relative flex w-full max-w-md flex-col items-center justify-center rounded-xl border border-slate-200 bg-white p-10 text-center shadow-lg mx-auto overflow-hidden transition-all duration-500 hover:border-blue-400 hover:shadow-[0_0_40px_rgba(59,130,246,0.3)]"
      variants={containerVariants}
      initial="hidden"
      whileInView="visible"
      whileHover={{ y: -5 }}
      viewport={{ once: true, margin: "-50px" }}
      aria-labelledby="state-title"
    >
      {/* Animated shiny border effect on hover */}
      <div className="absolute inset-0 bg-gradient-to-r from-blue-500/0 via-blue-500/10 to-blue-500/0 -translate-x-[150%] group-hover:translate-x-[150%] transition-transform duration-1000 ease-in-out pointer-events-none" />
      
      <motion.img
        src={imageUrl}
        alt="illustration"
        className="mb-8 h-40 w-40 object-contain drop-shadow-[0_15px_15px_rgba(0,0,0,0.15)]"
        variants={itemVariants}
      />

      <motion.h2
        id="state-title"
        className="text-2xl font-bold text-slate-900 tracking-tight"
        variants={itemVariants}
      >
        {title}
      </motion.h2>

      <motion.p
        className="mt-3 text-[15px] text-slate-600 leading-relaxed max-w-[280px]"
        variants={itemVariants}
      >
        {description}
      </motion.p>

      <motion.div
        className="mt-8 flex w-full flex-col gap-3 sm:flex-row sm:justify-center"
        variants={itemVariants}
      >
        <Button
          variant="outline"
          className="w-full sm:w-auto bg-transparent border-slate-200 text-slate-700 hover:bg-slate-50 hover:text-slate-900"
          onClick={secondaryAction.onClick}
        >
          {secondaryAction.text}
        </Button>
        <Button
          className="w-full sm:w-auto bg-blue-600 text-white hover:bg-blue-700 shadow-md shadow-blue-600/20"
          onClick={primaryAction.onClick}
        >
          {primaryAction.icon && <span className="mr-2 flex items-center justify-center">{primaryAction.icon}</span>}
          {primaryAction.text}
        </Button>
      </motion.div>
    </motion.div>
  );
};
