"use client";

import * as React from "react";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import * as z from "zod";
import { motion } from "framer-motion";
import { Button } from "@/components/ui/button";
import {
  Form,
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from "@/components/ui/form";
import { Input } from "@/components/ui/input";
import { Checkbox } from "@/components/ui/checkbox";
import { Loader2, ArrowLeft } from "lucide-react";
import { GridPattern } from "@/components/ui/grid-pattern";
import { cn } from "@/lib/utils";

const loginSchema = z.object({
  email: z.string().email({ message: "Please enter a valid email." }),
  password: z.string().min(1, { message: "Password is required." }),
  remember: z.boolean().default(false).optional(),
});

const registerSchema = z.object({
  name: z.string().min(2, { message: "Name must be at least 2 characters." }),
  phone: z.string().min(8, { message: "Please enter a valid phone number." }),
  email: z.string().email({ message: "Please enter a valid email." }),
  password: z.string().min(1, { message: "Password is required." }),
});

type LoginFormValues = z.infer<typeof loginSchema>;
type RegisterFormValues = z.infer<typeof registerSchema>;

interface AuthFormSplitScreenProps {
  initialIsSignIn?: boolean;
  csrfToken: string;
  loginUrl?: string;
  registerUrl?: string;
  oldValues?: any;
  errors?: string[];
  successMessage?: string;
  signInContent?: any;
  signUpContent?: any;
}

export function AuthFormSplitScreen({
  initialIsSignIn = true,
  csrfToken,
  loginUrl = "/login",
  registerUrl = "/register",
  oldValues = {},
  errors = [],
  successMessage = "",
  signInContent = {},
  signUpContent = {},
}: AuthFormSplitScreenProps) {
  const [isSignIn, setIsSignIn] = React.useState(initialIsSignIn);
  const [isLoading, setIsLoading] = React.useState(false);
  const formRef = React.useRef<HTMLFormElement>(null);

  const toggleForm = () => setIsSignIn((prev) => !prev);

  const loginForm = useForm<LoginFormValues>({
    resolver: zodResolver(loginSchema),
    defaultValues: {
      email: oldValues.email || "",
      password: "",
      remember: false,
    },
  });

  const registerForm = useForm<RegisterFormValues>({
    resolver: zodResolver(registerSchema),
    defaultValues: {
      name: oldValues.name || "",
      phone: oldValues.phone || "",
      email: oldValues.email || "",
      password: "",
    },
  });

  const handleLoginSubmit = async (values: LoginFormValues) => {
    setIsLoading(true);
    const form = formRef.current;
    if (!form) return;
    
    // Set nilai ke input yang ada di form
    const emailInput = form.querySelector('input[name="email"]') as HTMLInputElement;
    const passwordInput = form.querySelector('input[name="password"]') as HTMLInputElement;
    if (emailInput) {
        emailInput.value = values.email;
        emailInput.removeAttribute('disabled');
    }
    if (passwordInput) {
        passwordInput.value = values.password;
        passwordInput.removeAttribute('disabled');
    }
    
    form.submit();
  };

  const handleRegisterSubmit = async (values: RegisterFormValues) => {
    setIsLoading(true);
    const form = formRef.current;
    if (!form) return;
    
    const nameInput = form.querySelector('input[name="name"]') as HTMLInputElement;
    const phoneInput = form.querySelector('input[name="phone"]') as HTMLInputElement;
    const emailInput = form.querySelector('input[name="email"]') as HTMLInputElement;
    const passwordInput = form.querySelector('input[name="password"]') as HTMLInputElement;
    if (nameInput) {
        nameInput.value = values.name;
        nameInput.removeAttribute('disabled');
    }
    if (phoneInput) {
        phoneInput.value = values.phone;
        phoneInput.removeAttribute('disabled');
    }
    if (emailInput) {
        emailInput.value = values.email;
        emailInput.removeAttribute('disabled');
    }
    if (passwordInput) {
        passwordInput.value = values.password;
        passwordInput.removeAttribute('disabled');
    }
    
    form.submit();
  };

  const containerVariants = {
    hidden: { opacity: 0 },
    visible: { opacity: 1, transition: { staggerChildren: 0.1 } },
  };

  const itemVariants = {
    hidden: { y: 20, opacity: 0 },
    visible: { y: 0, opacity: 1 },
  };

  const currentImage = isSignIn
    ? signInContent?.image?.src || "https://images.unsplash.com/photo-1714715350295-5f00e902f0d7?q=80&w=1200&auto=format&fit=crop"
    : signUpContent?.image?.src || "https://images.unsplash.com/photo-1562577309-2592ab84b1bc?q=80&w=1200&auto=format&fit=crop";

  return (
    <div className="relative flex min-h-screen w-full flex-col md:flex-row bg-white text-slate-900 overflow-hidden">
      {/* Left Panel: Form */}
      <div className="relative flex w-full flex-col items-center justify-center p-8 md:w-1/2 pt-20 md:pt-8 bg-background">
        <GridPattern
          squares={[
            [4, 4],
            [5, 1],
            [8, 2],
            [5, 3],
            [5, 5],
            [10, 10],
            [12, 15],
            [15, 10],
            [10, 15],
            [15, 10],
            [10, 15],
            [15, 10],
          ]}
          className={cn(
            "[mask-image:radial-gradient(600px_circle_at_center,white,transparent)]",
            "inset-x-0 inset-y-[-30%] h-[200%] skew-y-12",
          )}
        />
        {/* Back Button */}
        <div className="absolute top-8 left-8 md:top-10 md:left-10 z-10">
          <a
            href="/"
            className="inline-flex items-center gap-2 text-sm font-medium text-slate-600 bg-white/90 hover:bg-white border border-slate-200 hover:border-slate-300 hover:text-slate-900 transition-all duration-200 rounded-xl px-4 py-2.5 shadow-sm hover:shadow active:scale-95 group"
          >
            <ArrowLeft className="h-4 w-4 transition-transform group-hover:-translate-x-1" />
            <span>Kembali ke Beranda</span>
          </a>
        </div>
        <div className="w-full max-w-md z-10">
          <motion.div
            key={isSignIn ? "login" : "register"}
            variants={containerVariants}
            initial="hidden"
            animate="visible"
            className="flex flex-col gap-6"
          >
            <motion.div variants={itemVariants} className="mb-4">
              <a href="/" className="flex items-center gap-2">
                <div className="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-primary-500 to-emerald-500 text-sm font-bold text-white shadow-md">
                  J
                </div>
                <span className="text-xl font-bold text-slate-900">Jaya Mandiri</span>
              </a>
            </motion.div>

            <motion.div variants={itemVariants} className="text-left">
              <h1 className="text-3xl font-bold tracking-tight text-slate-900">
                {isSignIn ? "Welcome Back!" : "Create an Account"}
              </h1>
              <p className="mt-2 text-sm text-slate-500">
                {isSignIn
                  ? "Sign in by entering the information below."
                  : "Sign up to start printing online with us."}
              </p>
            </motion.div>

            {errors?.length > 0 && (
              <motion.div variants={itemVariants} className="rounded-xl border border-red-100 bg-red-50 p-4 text-sm text-red-600">
                {errors.map((msg, i) => (
                  <p key={i}>{msg}</p>
                ))}
              </motion.div>
            )}

            {successMessage && isSignIn && (
              <motion.div variants={itemVariants} className="rounded-xl border border-emerald-100 bg-emerald-50 p-4 text-sm text-emerald-700">
                {successMessage}
              </motion.div>
            )}

            {isSignIn ? (
              <Form {...loginForm}>
                <form
                  ref={formRef}
                  action={loginUrl}
                  method="POST"
                  onSubmit={loginForm.handleSubmit(handleLoginSubmit)}
                  className="space-y-4"
                >
                  <input type="hidden" name="_token" value={csrfToken} />
                  
                  <motion.div variants={itemVariants}>
                    <FormField
                      control={loginForm.control}
                      name="email"
                      render={({ field }) => (
                        <FormItem>
                          <FormLabel>Email Address</FormLabel>
                          <FormControl>
                            <Input placeholder="email@example.com" {...field} disabled={isLoading} />
                          </FormControl>
                          <FormMessage />
                        </FormItem>
                      )}
                    />
                  </motion.div>

                  <motion.div variants={itemVariants}>
                    <FormField
                      control={loginForm.control}
                      name="password"
                      render={({ field }) => (
                        <FormItem>
                          <FormLabel>Password</FormLabel>
                          <FormControl>
                            <Input type="password" placeholder="••••••••••••" {...field} disabled={isLoading} />
                          </FormControl>
                          <FormMessage />
                        </FormItem>
                      )}
                    />
                  </motion.div>

                  <motion.div variants={itemVariants} className="flex items-center">
                    <FormField
                      control={loginForm.control}
                      name="remember"
                      render={({ field }) => (
                        <FormItem className="flex flex-row items-start space-x-3 space-y-0">
                          <FormControl>
                            <Checkbox checked={field.value} onCheckedChange={field.onChange} disabled={isLoading} name="remember" value="1" />
                          </FormControl>
                          <div className="space-y-1 leading-none">
                            <FormLabel className="font-normal cursor-pointer">Remember Me</FormLabel>
                          </div>
                        </FormItem>
                      )}
                    />
                  </motion.div>

                  <motion.div variants={itemVariants} className="pt-2">
                    <Button type="submit" className="w-full bg-blue-600 hover:bg-blue-700 text-white h-11 rounded-xl" disabled={isLoading}>
                      {isLoading && <Loader2 className="mr-2 h-4 w-4 animate-spin" />}
                      Continue
                    </Button>
                  </motion.div>
                </form>
              </Form>
            ) : (
              <Form {...registerForm}>
                <form
                  ref={formRef}
                  action={registerUrl}
                  method="POST"
                  onSubmit={registerForm.handleSubmit(handleRegisterSubmit)}
                  className="space-y-4"
                >
                  <input type="hidden" name="_token" value={csrfToken} />

                  <motion.div variants={itemVariants}>
                    <FormField
                      control={registerForm.control}
                      name="name"
                      render={({ field }) => (
                        <FormItem>
                          <FormLabel>Full Name</FormLabel>
                          <FormControl>
                            <Input placeholder="John Doe" {...field} disabled={isLoading} />
                          </FormControl>
                          <FormMessage />
                        </FormItem>
                      )}
                    />
                  </motion.div>

                  <motion.div variants={itemVariants}>
                    <FormField
                      control={registerForm.control}
                      name="phone"
                      render={({ field }) => (
                        <FormItem>
                          <FormLabel>Phone Number</FormLabel>
                          <FormControl>
                            <Input placeholder="08123456789" {...field} disabled={isLoading} />
                          </FormControl>
                          <FormMessage />
                        </FormItem>
                      )}
                    />
                  </motion.div>

                  <motion.div variants={itemVariants}>
                    <FormField
                      control={registerForm.control}
                      name="email"
                      render={({ field }) => (
                        <FormItem>
                          <FormLabel>Email Address</FormLabel>
                          <FormControl>
                            <Input placeholder="email@example.com" {...field} disabled={isLoading} />
                          </FormControl>
                          <FormMessage />
                        </FormItem>
                      )}
                    />
                  </motion.div>

                  <motion.div variants={itemVariants}>
                    <FormField
                      control={registerForm.control}
                      name="password"
                      render={({ field }) => (
                        <FormItem>
                          <FormLabel>Password</FormLabel>
                          <FormControl>
                            <Input type="password" placeholder="••••••••••••" {...field} disabled={isLoading} />
                          </FormControl>
                          <FormMessage />
                        </FormItem>
                      )}
                    />
                  </motion.div>

                  <motion.div variants={itemVariants} className="pt-2">
                    <Button type="submit" className="w-full bg-blue-600 hover:bg-blue-700 text-white h-11 rounded-xl" disabled={isLoading}>
                      {isLoading && <Loader2 className="mr-2 h-4 w-4 animate-spin" />}
                      Create Account
                    </Button>
                  </motion.div>
                </form>
              </Form>
            )}

            <motion.p variants={itemVariants} className="px-8 mt-4 text-center text-sm text-slate-500">
              {isSignIn ? "Don't have an account?" : "Already have an account?"}{" "}
              <button
                type="button"
                onClick={toggleForm}
                className="font-medium text-blue-600 hover:underline bg-transparent border-none p-0 cursor-pointer"
              >
                {isSignIn ? "Create one here" : "Sign in here"}
              </button>
              .
            </motion.p>
          </motion.div>
        </div>
      </div>

      {/* Right Panel: Image */}
      <div className="relative hidden w-1/2 md:block overflow-hidden">
        <motion.div
          key={currentImage}
          initial={{ opacity: 0, scale: 1.05 }}
          animate={{ opacity: 1, scale: 1 }}
          transition={{ duration: 0.8 }}
          className="absolute inset-0"
        >
          <img
            src={currentImage}
            alt="Authentication background"
            className="h-full w-full object-cover"
          />
          <div className="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-slate-900/20 to-transparent" />
        </motion.div>
      </div>
    </div>
  );
}
