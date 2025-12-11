<!DOCTYPE html>
<html lang="vi" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GhienCine') - Trải nghiệm điện ảnh đỉnh cao</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="icon" href="{{ asset('ticket.ico') }}" type="image/x-icon">

    <style>
        /* Thiết lập Font chữ */
        body { font-family: 'Be Vietnam Pro', sans-serif; }
        h1, h2, h3, h4, h5, h6, .font-heading { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Animation cho Toast */
        @keyframes slideInRight {
            from { transform: translateX(100%) scale(0.9); opacity: 0; }
            to   { transform: translateX(0) scale(1); opacity: 1; }
        }
        @keyframes fadeOutRight {
            from { transform: translateX(0); opacity: 1; }
            to   { transform: translateX(100%); opacity: 0; }
        }
        .toast-enter { animation: slideInRight 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }
        .toast-leave { animation: fadeOutRight 0.4s ease-in forwards; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c084fc; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #9333ea; }
    </style>
</head>
<body class="bg-gray-50 text-slate-800 antialiased selection:bg-purple-500 selection:text-white flex flex-col min-h-screen">

    @include('layouts.navbar')

    <main class="flex-grow pt-24">
        @yield('content')
    </main>

    @include('layouts.footer')

    <div class="fixed bottom-6 right-6 z-[60] flex flex-col gap-3">
        
        @if(session('success'))
            <div id="toast-success" class="toast-enter bg-white/90 backdrop-blur-md border-l-4 border-green-500 shadow-xl rounded-lg p-4 pr-8 min-w-[320px] flex items-start gap-4 transform transition-all hover:scale-[1.02]">
                <div class="bg-green-100 rounded-full p-2 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <h4 class="text-gray-900 font-bold text-sm">Thành công!</h4>
                    <p class="text-gray-600 text-sm mt-1">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div id="toast-error" class="toast-enter bg-white/90 backdrop-blur-md border-l-4 border-red-500 shadow-xl rounded-lg p-4 pr-8 min-w-[320px] flex items-start gap-4 transform transition-all hover:scale-[1.02]">
                <div class="bg-red-100 rounded-full p-2 text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <div>
                    <h4 class="text-gray-900 font-bold text-sm">Đã có lỗi xảy ra</h4>
                    <ul class="text-gray-600 text-sm mt-1 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>

    <script>
        // Tự động ẩn Toast sau 4s
        const hideToast = (id) => {
            const el = document.getElementById(id);
            if(el) {
                setTimeout(() => {
                    el.classList.remove('toast-enter');
                    el.classList.add('toast-leave');
                    setTimeout(() => el.remove(), 400); 
                }, 4000);
            }
        };
        hideToast('toast-success');
        hideToast('toast-error');
    </script>
</body>
</html>