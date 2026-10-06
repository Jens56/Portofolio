<x-layout>
    <div class="min-h-screen bg-gradient-to-b from-gray-900 to-black flex flex-col">
        <div class="flex-grow px-5 md:px-12 pt-10 pb-20">
            <div class="mb-10">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-3 bg-gradient-to-r from-yellow-400 to-yellow-300 bg-clip-text text-transparent">Blog</h1>
                <p class="text-gray-300 text-sm md:text-lg">Delen en ontmoeten met de community</p>
            </div>
            <div class="mb-10">
                <a href="/post" class="bg-gradient-to-r from-yellow-600 to-yellow-500 text-white px-6 py-3 rounded-lg hover:from-yellow-700 hover:to-yellow-600 transition-all duration-300 inline-flex items-center gap-2 font-semibold shadow-lg text-sm md:text-base">
                    <i class="las la-plus"></i> Nieuw bericht
                </a>
            </div>
            @if(isset($posts) && $posts->count())
                <div class="grid gap-6 grid-cols-1 md:grid-cols-2 lg:grid-cols-2">
                    @foreach($posts as $post)
                        <div class="bg-gradient-to-br from-gray-800 to-gray-900 p-5 md:p-6 rounded-xl border border-gray-700 hover:border-yellow-500 transition-all duration-300 hover:shadow-xl hover:scale-105">
                            <h2 class="text-xl md:text-2xl font-bold text-white mb-2 truncate">{{ $post->title }}</h2>
                            <div class="flex items-center gap-2 md:gap-3 mb-4 text-xs md:text-sm text-gray-400 flex-wrap">
                                <span class="font-semibold {{ $post->author_id ? 'text-yellow-400' : 'text-gray-500' }} truncate">{{ $post->author->name ?? 'Anoniem' }}</span>
                                <span class="text-gray-600 flex-shrink-0">•</span>
                                <span class="flex-shrink-0">{{ $post->created_at->format('d-m-Y H:i') }}</span>
                            </div>
                            <p class="text-gray-300 mb-6 line-clamp-3 text-sm md:text-base">{{ $post->content }}</p>
                            <div class="flex items-center justify-between pt-4 border-t border-gray-700 gap-2 flex-wrap">
                                <div class="flex gap-4">
                                    <a href="/blog/{{ $post->id }}" class="inline-flex items-center gap-2 text-yellow-400 hover:text-yellow-300 transition-colors duration-200 font-medium text-xs md:text-sm">
                                        <i class="las la-comments"></i>
                                        <span class="hidden sm:inline">Reageer</span>
                                    </a>
                                </div>
                                @auth
                                    @if(auth()->id() === $post->author_id)
                                        <form action="{{ route('blog.delete', $post->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 cursor-pointer text-red-400 hover:text-red-300 transition-colors duration-200 font-medium text-xs md:text-sm" onclick="return confirm('Weet je zeker dat je dit bericht wilt verwijderen?')">
                                                <i class="las la-trash-alt"></i>
                                                <span class="hidden sm:inline">Verwijderen</span>
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-gradient-to-br from-gray-800 to-gray-900 p-8 md:p-12 rounded-xl border border-gray-700 text-center">
                    <i class="las la-inbox text-4xl md:text-5xl text-gray-500 mb-4"></i>
                    <p class="text-gray-400 text-base md:text-lg mb-4">Nog geen berichten...</p>
                    <p class="text-gray-500 mb-6 text-sm md:text-base">Wees de eerste om het gesprek te starten!</p>
                    <a href="/post" class="bg-gradient-to-r from-yellow-600 to-yellow-500 text-white px-6 py-3 rounded-lg hover:from-yellow-700 hover:to-yellow-600 transition-all duration-300 inline-flex items-center gap-2 font-semibold shadow-lg">
                        <i class="las la-pen"></i> Plaats je eerste bericht
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layout>