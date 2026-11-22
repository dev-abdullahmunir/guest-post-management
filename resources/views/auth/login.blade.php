@php
  $appName = !empty($appSettings['app_name']) ? $appSettings['app_name'] : 'GuestPost Pro';
  $appTagline = !empty($appSettings['app_tagline']) ? $appSettings['app_tagline'] : 'Publish • Grow • Rank';
  $appLogo = $appSettings['app_logo'] ?? '';
  $appIcon = !empty($appSettings['app_icon']) ? $appSettings['app_icon'] : 'fa-solid fa-chart-line';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In - {{ $appName }}</title>
  
  <!-- Plus Jakarta Sans Font & FontAwesome 6 -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['"Plus Jakarta Sans"', 'sans-serif'],
            mono: ['"JetBrains Mono"', 'monospace'],
          },
          colors: {
            brand: {
              primary: '#4f46e5',
              dark: '#111827',
            }
          }
        }
      }
    }
  </script>

  <style>
    body {
      background-color: #f4f6fa;
      font-family: 'Plus Jakarta Sans', sans-serif;
      color: #1e293b;
    }
  </style>
</head>
<body class="min-h-screen bg-[#f4f6fa] flex items-center justify-center p-4 sm:p-6 antialiased">

  <div class="w-full max-w-md space-y-6">

    <!-- Brand Header -->
    <div class="text-center space-y-2">
      <div class="inline-flex items-center justify-center">
        @if(!empty($appLogo))
          <img src="{{ $appLogo }}" alt="{{ $appName }}" class="h-10 max-w-[200px] object-contain">
        @else
          <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center shadow-sm">
            <i class="{{ $appIcon }} text-2xl"></i>
          </div>
        @endif
      </div>
      <h1 class="text-2xl font-black text-slate-800 tracking-tight">{{ $appName }}</h1>
      <p class="text-xs text-slate-400 font-semibold tracking-wide">{{ $appTagline }}</p>
    </div>

    <!-- Login Card (Floating White Card) -->
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-[0_10px_35px_-5px_rgba(0,0,0,0.05)] p-7 sm:p-8 space-y-6">
      
      <div>
        <h2 class="text-lg font-black text-slate-800">Welcome Back</h2>
        <p class="text-xs text-slate-500 mt-0.5">Please sign in to access your administrative workspace.</p>
      </div>

      <!-- Flash Error Banner -->
      @if(!empty($error))
        <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs flex items-center gap-2.5">
          <i class="fa-solid fa-circle-exclamation text-rose-500 text-sm shrink-0"></i>
          <span>{{ $error }}</span>
        </div>
      @endif

      <!-- Login Form -->
      <form action="/login" method="POST" class="space-y-4">
        
        <!-- Email Input -->
        <div class="space-y-1.5">
          <label for="email" class="block text-xs font-bold text-slate-700">Email Address</label>
          <div class="relative">
            <i class="fa-regular fa-envelope absolute left-3.5 top-3.5 text-xs text-slate-400"></i>
            <input 
              type="email" 
              name="email" 
              id="email" 
              required
              value="admin@guestpost.com"
              placeholder="you@company.com" 
              class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3.5 py-2.5 text-xs text-slate-800 font-medium placeholder-slate-400 focus:outline-none focus:border-indigo-600 focus:bg-white transition"
            />
          </div>
        </div>

        <!-- Password Input -->
        <div class="space-y-1.5">
          <div class="flex items-center justify-between">
            <label for="password" class="block text-xs font-bold text-slate-700">Password</label>
            <span class="text-[11px] font-semibold text-slate-400">Default: password123</span>
          </div>
          <div class="relative">
            <i class="fa-solid fa-lock absolute left-3.5 top-3.5 text-xs text-slate-400"></i>
            <input 
              type="password" 
              name="password" 
              id="password" 
              required
              value="password123"
              placeholder="••••••••••••" 
              class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-10 py-2.5 text-xs text-slate-800 font-medium placeholder-slate-400 focus:outline-none focus:border-indigo-600 focus:bg-white transition"
            />
            <button 
              type="button" 
              onclick="togglePasswordVisibility()" 
              class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 p-1 text-xs transition"
              title="Show/Hide Password"
            >
              <i id="passwordToggleIcon" class="fa-regular fa-eye"></i>
            </button>
          </div>
        </div>

        <!-- Remember Me Checkbox -->
        <div class="flex items-center justify-between pt-1">
          <label class="flex items-center gap-2 cursor-pointer select-none">
            <input 
              type="checkbox" 
              name="remember" 
              value="1" 
              checked
              class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer"
            />
            <span class="text-xs font-semibold text-slate-600">Keep me signed in</span>
          </label>
          <span class="text-[11px] font-bold text-emerald-600 flex items-center gap-1">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Sanctum Protected
          </span>
        </div>

        <!-- Submit Button -->
        <button 
          type="submit" 
          class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs py-3 rounded-xl shadow-lg shadow-indigo-600/25 flex items-center justify-center gap-2 transition hover:scale-[1.01] active:scale-[0.99]"
        >
          <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
          <span>Sign In to Workspace</span>
        </button>

      </form>

      <!-- Quick Demo Credentials Shortcut -->
      <div class="pt-4 border-t border-slate-100 space-y-2">
        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 text-center">Quick Demo Credentials</p>
        <button 
          type="button" 
          onclick="fillDemoCredentials('admin@guestpost.com', 'password123')" 
          class="w-full bg-slate-50 hover:bg-slate-100 border border-slate-200/80 rounded-xl p-2.5 text-left flex items-center justify-between transition group"
        >
          <div class="flex items-center gap-2.5">
            <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold">
              <i class="fa-solid fa-crown text-[10px]"></i>
            </div>
            <div>
              <p class="text-xs font-black text-slate-700 group-hover:text-indigo-600 transition">Super Administrator</p>
              <p class="text-[10px] text-slate-400 font-mono">admin@guestpost.com</p>
            </div>
          </div>
          <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-100">
            Auto Fill
          </span>
        </button>
      </div>

    </div>

    <!-- Security & Copyright Footer -->
    <div class="text-center space-y-1">
      <div class="inline-flex items-center gap-3 text-[11px] text-slate-400 font-semibold">
        <span><i class="fa-solid fa-shield-halved text-emerald-500 mr-1"></i> 256-Bit SSL</span>
        <span>•</span>
        <span><i class="fa-solid fa-key text-indigo-500 mr-1"></i> Sanctum Tokens</span>
        <span>•</span>
        <span><i class="fa-solid fa-lock text-slate-400 mr-1"></i> Session Guard</span>
      </div>
      <p class="text-[11px] text-slate-400 font-medium">© {{ date('Y') }} {{ $appName }}. All rights reserved.</p>
    </div>

  </div>

  <script>
    function togglePasswordVisibility() {
      const input = document.getElementById('password');
      const icon = document.getElementById('passwordToggleIcon');
      if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fa-regular fa-eye-slash text-indigo-600';
      } else {
        input.type = 'password';
        icon.className = 'fa-regular fa-eye';
      }
    }

    function fillDemoCredentials(email, password) {
      document.getElementById('email').value = email;
      document.getElementById('password').value = password;
    }
  </script>

</body>
</html>
