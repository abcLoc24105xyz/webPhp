{{-- resources/views/movie/partials/showtimes.blade.php --}}
@forelse($shows as $cinemaId => $showGroup)
    @php 
        $cinema = $showGroup->first()->cinema;
        // Xử lý ngày chiếu sớm (nếu có)
        $isEarlyDate = $isEarlyDate ?? false;
    @endphp

    <div class="group bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden border border-gray-100 
                hover:shadow-3xl hover:shadow-purple-500/30 transition-all duration-500 mb-10">
        
        {{-- Header rạp --}}
        <div class="bg-gradient-to-r {{ $isEarlyDate ? 'from-yellow-400 to-orange-500' : 'from-purple-600 to-pink-600' }} p-6 md:p-8">
            <h3 class="text-2xl md:text-3xl font-heading font-extrabold text-white tracking-tight">
                {{ $cinema->cinema_name }}
                @if($isEarlyDate)
                    <span class="ml-3 inline-block bg-red-600 text-white text-xs px-3 py-1 rounded-full font-black animate-pulse">
                        SUẤT SỚM
                    </span>
                @endif
            </h3>
            <p class="mt-2 text-white/90 text-sm md:text-base opacity-90 line-clamp-1">
                {{ $cinema->address }}
            </p>
        </div>

        {{-- Danh sách suất chiếu --}}
        <div class="p-6 md:p-10">
            <div class="flex flex-wrap gap-4 md:gap-6 justify-center lg:justify-start">
                @foreach($showGroup as $show)
                    @php
                        // SỬA LỖI CHÍNH: $show->start_time là string → dùng Carbon::parse()
                        $startTime = \Carbon\Carbon::parse($show->start_time);
                    @endphp

                    @auth
                        <a href="{{ route('seat.selection', $show->show_id) }}"
                           class="group/show relative overflow-hidden rounded-2xl px-8 py-6 text-center
                                  bg-gradient-to-r from-purple-600 to-pink-600 text-white
                                  shadow-xl transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-purple-500/50
                                  min-w-[160px]">
                            
                            <div class="text-4xl md:text-5xl font-black tracking-tight">
                                {{ $startTime->format('H:i') }}
                            </div>

                            <div class="mt-4 space-y-2">
                                <p class="text-lg font-bold opacity-90 truncate">
                                    {{ $show->room->room_name ?? 'Phòng thường' }}
                                </p>
                                <p class="text-sm bg-white/20 px-4 py-1.5 rounded-full inline-block font-medium">
                                    Còn <strong class="text-yellow-300">{{ $show->remaining_seats }}</strong> ghế
                                </p>
                            </div>

                            {{-- Hiệu ứng sáng khi hover --}}
                            <div class="absolute inset-0 bg-white opacity-0 group-hover/show:opacity-10 transition-opacity duration-500 pointer-events-none"></div>
                        </a>
                    @else
                        <a href="{{ route('login') }}?redirect={{ url()->current() }}"
                           class="flex flex-col justify-center items-center rounded-2xl px-8 py-6 text-center
                                  bg-gray-50 border-4 border-dashed border-gray-300 text-gray-600 font-bold
                                  hover:border-purple-400 hover:bg-purple-50 transition-all duration-300
                                  min-w-[160px] min-h-[140px]">
                            <div class="text-4xl md:text-5xl font-black">
                                {{ $startTime->format('H:i') }}
                            </div>
                            <div class="mt-4 text-base">Đăng nhập để đặt vé</div>
                        </a>
                    @endauth
                @endforeach
            </div>
        </div>
    </div>

@empty
    <div class="text-center py-32">
        <div class="inline-block bg-white/10 backdrop-blur-xl rounded-3xl px-16 py-20 border border-white/20">
            <svg class="w-24 h-24 mx-auto text-gray-500 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
            <p class="text-4xl md:text-5xl font-heading font-extrabold text-gray-400">
                Không có suất chiếu nào
            </p>
            <p class="text-xl text-gray-500 mt-6">Hãy chọn ngày hoặc rạp khác nhé!</p>
        </div>
    </div>
@endforelse