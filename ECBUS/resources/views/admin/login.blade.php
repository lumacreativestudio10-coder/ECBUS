<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | ECBUS</title>
    <!-- Tailwind CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4 font-sans text-dark-text">

    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="bg-primary-maroon px-8 py-10 text-center relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-primary-gold/20 rounded-full blur-xl -ml-10 -mb-10"></div>
            
            <a href="{{ route('home') }}" class="inline-block relative z-10">
                <span class="text-3xl font-black text-white tracking-tighter block mb-2">EC<span class="text-primary-gold">BUS</span><span class="text-xs font-bold align-top ml-1 opacity-70">ADMIN</span></span>
            </a>
            <p class="text-white/80 text-sm font-medium relative z-10">Secure Operations Center</p>
        </div>

        <div class="p-8">
            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm font-bold flex items-center">
                    <i data-lucide="alert-circle" class="w-4 h-4 mr-2 flex-shrink-0"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-5">
                @csrf
                
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">Email Address</label>
                    <div class="relative">
                        <i data-lucide="mail" class="w-5 h-5 absolute left-3 top-3 text-gray-400"></i>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-maroon/20 focus:border-primary-maroon outline-none transition bg-gray-50 focus:bg-white font-medium">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">Password</label>
                    <div class="relative">
                        <i data-lucide="lock" class="w-5 h-5 absolute left-3 top-3 text-gray-400"></i>
                        <input type="password" name="password" required class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-maroon/20 focus:border-primary-maroon outline-none transition bg-gray-50 focus:bg-white font-medium">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center cursor-pointer group">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-primary-maroon focus:ring-primary-maroon transition cursor-pointer">
                        <span class="ml-2 text-sm text-gray-500 font-medium group-hover:text-dark-text transition">Remember me</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-primary-maroon hover:bg-dark-maroon text-white font-bold py-3.5 rounded-xl shadow-lg shadow-primary-maroon/30 transition transform hover:-translate-y-0.5 active:translate-y-0">
                    Sign In to Dashboard
                </button>
            </form>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
