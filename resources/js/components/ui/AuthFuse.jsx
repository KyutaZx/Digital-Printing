import React, { useEffect, useId, useState } from 'react';
import { Slot } from '@radix-ui/react-slot';
import * as LabelPrimitive from '@radix-ui/react-label';
import { cva } from 'class-variance-authority';
import { cn } from '../../lib/utils';

function Typewriter({ text, speed = 100, cursor = '|', loop = false, deleteSpeed = 50, delay = 1500, className }) {
  const [displayText, setDisplayText] = useState('');
  const [currentIndex, setCurrentIndex] = useState(0);
  const [isDeleting, setIsDeleting] = useState(false);
  const [textArrayIndex, setTextArrayIndex] = useState(0);

  const textArray = Array.isArray(text) ? text : [text];
  const currentText = textArray[textArrayIndex] || '';

  useEffect(() => {
    if (!currentText) return;

    const timeout = setTimeout(
      () => {
        if (!isDeleting) {
          if (currentIndex < currentText.length) {
            setDisplayText((prev) => prev + currentText[currentIndex]);
            setCurrentIndex((prev) => prev + 1);
          } else if (loop) {
            setTimeout(() => setIsDeleting(true), delay);
          }
        } else if (displayText.length > 0) {
          setDisplayText((prev) => prev.slice(0, -1));
        } else {
          setIsDeleting(false);
          setCurrentIndex(0);
          setTextArrayIndex((prev) => (prev + 1) % textArray.length);
        }
      },
      isDeleting ? deleteSpeed : speed
    );

    return () => clearTimeout(timeout);
  }, [currentIndex, isDeleting, currentText, loop, speed, deleteSpeed, delay, displayText, textArray.length]);

  return (
    <span className={className}>
      {displayText}
      <span className="animate-pulse">{cursor}</span>
    </span>
  );
}

const labelVariants = cva('text-sm font-semibold leading-none text-slate-700');

const Label = React.forwardRef(({ className, ...props }, ref) => (
  <LabelPrimitive.Root ref={ref} className={cn(labelVariants(), className)} {...props} />
));
Label.displayName = LabelPrimitive.Root.displayName;

const buttonVariants = cva(
  'inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-xl text-sm font-semibold transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50',
  {
    variants: {
      variant: {
        default: 'bg-primary-600 text-white hover:bg-primary-700 shadow-md shadow-primary-600/20',
        outline: 'border border-slate-200 bg-white text-slate-800 hover:bg-slate-50',
        link: 'text-primary-600 underline-offset-4 hover:underline p-0 h-auto',
      },
      size: {
        default: 'h-11 px-4 py-2',
        lg: 'h-12 px-6',
      },
    },
    defaultVariants: { variant: 'default', size: 'default' },
  }
);

const Button = React.forwardRef(({ className, variant, size, asChild = false, ...props }, ref) => {
  const Comp = asChild ? Slot : 'button';
  return <Comp className={cn(buttonVariants({ variant, size, className }))} ref={ref} {...props} />;
});
Button.displayName = 'Button';

const Input = React.forwardRef(({ className, type, ...props }, ref) => (
  <input
    type={type}
    className={cn(
      'flex h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 shadow-sm transition-colors placeholder:text-slate-400 focus:border-primary-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary-500/10 disabled:cursor-not-allowed disabled:opacity-50',
      className
    )}
    ref={ref}
    {...props}
  />
));
Input.displayName = 'Input';

const PasswordInput = React.forwardRef(({ className, label, ...props }, ref) => {
  const id = useId();
  const [showPassword, setShowPassword] = useState(false);

  return (
    <div className="grid w-full gap-2">
      {label && <Label htmlFor={id}>{label}</Label>}
      <div className="relative">
        <Input
          id={id}
          type={showPassword ? 'text' : 'password'}
          className={cn('pe-10', className)}
          ref={ref}
          {...props}
        />
        <button
          type="button"
          onClick={() => setShowPassword((prev) => !prev)}
          className="absolute inset-y-0 end-0 flex h-full w-10 items-center justify-center text-slate-400 hover:text-slate-600"
          aria-label={showPassword ? 'Sembunyikan password' : 'Tampilkan password'}
        >
          {showPassword ? (
            <svg className="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden>
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
            </svg>
          ) : (
            <svg className="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden>
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
          )}
        </button>
      </div>
    </div>
  );
});
PasswordInput.displayName = 'PasswordInput';

function ErrorBanner({ messages }) {
  if (!messages?.length) return null;
  return (
    <div className="rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-600">
      {messages.map((msg) => (
        <p key={msg}>{msg}</p>
      ))}
    </div>
  );
}

function SuccessBanner({ message }) {
  if (!message) return null;
  return (
    <div className="rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
      {message}
    </div>
  );
}

function SignInForm({ csrfToken, loginUrl, oldValues, errors }) {
  return (
    <form method="POST" action={loginUrl} autoComplete="on" className="flex flex-col gap-8">
      <div className="flex flex-col items-center gap-2 text-center">
        <a href="/" className="mb-2 flex items-center gap-2 lg:hidden">
          <div className="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-primary-500 to-emerald-500 text-sm font-bold text-white">
            J
          </div>
          <span className="text-lg font-bold text-slate-900">Jaya Mandiri</span>
        </a>
        <h1 className="text-2xl font-bold text-slate-900">Masuk ke Akun Anda</h1>
        <p className="text-sm text-slate-500">Masukkan email untuk melanjutkan pesanan cetak</p>
      </div>

      <ErrorBanner messages={errors} />
      <div className="grid gap-4">
        <input type="hidden" name="_token" value={csrfToken} />
        <div className="grid gap-2">
          <Label htmlFor="email">Email</Label>
          <Input
            id="email"
            name="email"
            type="email"
            defaultValue={oldValues?.email || ''}
            placeholder="email@contoh.com"
            required
            autoComplete="email"
          />
        </div>
        <PasswordInput
          name="password"
          label="Password"
          required
          autoComplete="current-password"
          placeholder="Password"
        />
        <Button type="submit" className="mt-2 w-full">
          Masuk Sekarang
        </Button>
      </div>
    </form>
  );
}

function SignUpForm({ csrfToken, registerUrl, oldValues, errors }) {
  return (
    <form method="POST" action={registerUrl} autoComplete="on" className="flex flex-col gap-8">
      <div className="flex flex-col items-center gap-2 text-center">
        <a href="/" className="mb-2 flex items-center gap-2 lg:hidden">
          <div className="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-primary-500 to-emerald-500 text-sm font-bold text-white">
            J
          </div>
          <span className="text-lg font-bold text-slate-900">Jaya Mandiri</span>
        </a>
        <h1 className="text-2xl font-bold text-slate-900">Buat Akun Baru</h1>
        <p className="text-sm text-slate-500">Daftar untuk mulai pesan cetak online</p>
      </div>

      <ErrorBanner messages={errors} />
      <div className="grid gap-4">
        <input type="hidden" name="_token" value={csrfToken} />
        <div className="grid gap-2">
          <Label htmlFor="name">Nama Lengkap</Label>
          <Input
            id="name"
            name="name"
            type="text"
            defaultValue={oldValues?.name || ''}
            placeholder="Ahmad Setiawan"
            required
            autoComplete="name"
          />
        </div>
        <div className="grid gap-2">
          <Label htmlFor="phone">Nomor HP / WhatsApp</Label>
          <Input
            id="phone"
            name="phone"
            type="text"
            defaultValue={oldValues?.phone || ''}
            placeholder="08123456789"
            required
            autoComplete="tel"
          />
        </div>
        <div className="grid gap-2">
          <Label htmlFor="register-email">Email</Label>
          <Input
            id="register-email"
            name="email"
            type="email"
            defaultValue={oldValues?.email || ''}
            placeholder="email@contoh.com"
            required
            autoComplete="email"
          />
        </div>
        <PasswordInput
          name="password"
          label="Password"
          required
          autoComplete="new-password"
          placeholder="Min. 8 karakter"
        />
        <Button type="submit" className="mt-2 w-full !bg-emerald-600 hover:!bg-emerald-700">
          Daftar Sekarang
        </Button>
      </div>
    </form>
  );
}

function AuthFormContainer({ isSignIn, onToggle, formProps, successMessage }) {
  return (
    <div className="mx-auto grid w-full max-w-[380px] gap-2">
      {isSignIn && successMessage && <SuccessBanner message={successMessage} />}
      {isSignIn ? (
        <SignInForm {...formProps} />
      ) : (
        <SignUpForm {...formProps} />
      )}
      <div className="text-center text-sm text-slate-500">
        {isSignIn ? 'Belum punya akun?' : 'Sudah punya akun?'}{' '}
        <Button variant="link" type="button" className="pl-1" onClick={onToggle}>
          {isSignIn ? 'Daftar sekarang' : 'Masuk di sini'}
        </Button>
      </div>
    </div>
  );
}

const defaultSignInContent = {
  image: {
    src: 'https://images.unsplash.com/photo-1626785774573-4b799315345d?w=1200&q=80&auto=format&fit=crop',
    alt: 'Mesin digital printing Jaya Mandiri',
  },
  quote: {
    text: 'Selamat datang kembali! Perjalanan cetak impian Anda dilanjutkan.',
    author: 'Jaya Mandiri',
  },
};

const defaultSignUpContent = {
  image: {
    src: 'https://images.unsplash.com/photo-1562577309-2592ab84b1bc?w=1200&q=80&auto=format&fit=crop',
    alt: 'Produk cetak premium Jaya Mandiri',
  },
  quote: {
    text: 'Buat akun baru. Bab cetak impian Anda dimulai di sini.',
    author: 'Jaya Mandiri',
  },
};

export function AuthUI({
  initialIsSignIn = true,
  csrfToken,
  loginUrl = '/login',
  registerUrl = '/register',
  oldValues = {},
  errors = [],
  successMessage = '',
  signInContent = {},
  signUpContent = {},
}) {
  const [isSignIn, setIsSignIn] = useState(initialIsSignIn);
  const toggleForm = () => setIsSignIn((prev) => !prev);

  const finalSignInContent = {
    image: { ...defaultSignInContent.image, ...signInContent.image },
    quote: { ...defaultSignInContent.quote, ...signInContent.quote },
  };
  const finalSignUpContent = {
    image: { ...defaultSignUpContent.image, ...signUpContent.image },
    quote: { ...defaultSignUpContent.quote, ...signUpContent.quote },
  };

  const currentContent = isSignIn ? finalSignInContent : finalSignUpContent;

  const formProps = {
    csrfToken,
    loginUrl,
    registerUrl,
    oldValues,
    errors,
  };

  return (
    <div className="min-h-screen w-full md:grid md:grid-cols-2">
      <style>{`
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear { display: none; }
      `}</style>

      <div className="flex min-h-screen items-center justify-center p-6 md:min-h-0 md:p-12">
        <AuthFormContainer
          isSignIn={isSignIn}
          onToggle={toggleForm}
          formProps={formProps}
          successMessage={successMessage}
        />
      </div>

      <div
        className="relative hidden bg-cover bg-center transition-all duration-500 ease-in-out md:block"
        style={{ backgroundImage: `url(${currentContent.image.src})` }}
        key={currentContent.image.src}
      >
        <div className="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-primary-900/40 to-slate-900/20" />
        <div className="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-slate-900/90 to-transparent" />

        <div className="relative z-10 flex h-full flex-col justify-between p-10">
          <a href="/" className="flex w-fit items-center gap-3">
            <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-primary-600 text-white shadow-lg">
              J
            </div>
            <span className="text-xl font-bold text-white">
              Jaya<span className="text-primary-300">Mandiri</span>
            </span>
          </a>

          <blockquote className="space-y-3 text-center text-white md:text-left">
            <p className="text-lg font-medium leading-relaxed md:text-xl">
              “
              <Typewriter key={currentContent.quote.text} text={currentContent.quote.text} speed={55} />
              ”
            </p>
            <cite className="block text-sm font-normal not-italic text-slate-300">
              — {currentContent.quote.author}
            </cite>
          </blockquote>
        </div>
      </div>
    </div>
  );
}
