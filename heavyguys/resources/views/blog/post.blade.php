<x-layout>
    <div class="min-h-screen bg-gradient-to-b from-gray-900 to-black flex flex-col">
        <div class="flex-grow px-5 md:px-12 pt-10 pb-20">
            <a href="{{ route('blog') }}" class="text-yellow-400 hover:text-yellow-300 mb-6 inline-flex items-center gap-2 text-sm md:text-base">
                <i class="las la-arrow-left"></i> Terug naar blog
            </a>
            <div class="bg-gradient-to-br from-gray-800 to-gray-900 p-5 md:p-8 rounded-xl border border-gray-700 mb-8">
                <h1 class="text-2xl md:text-4xl font-bold text-white mb-4">{{ $post->title }}</h1>
                <div class="flex items-center gap-2 md:gap-3 mb-6 text-xs md:text-sm text-gray-400 flex-wrap">
                    <span class="font-semibold {{ $post->author_id ? 'text-yellow-400' : 'text-gray-500' }} truncate">{{ $post->author->name ?? 'Anoniem' }}</span>
                    <span class="text-gray-600 flex-shrink-0">•</span>
                    <span class="flex-shrink-0">{{ $post->created_at->format('d-m-Y H:i') }}</span>
                </div>
                <div class="text-gray-300 text-sm md:text-base leading-relaxed mb-6 break-words">
                    {{ $post->content }}
                </div>
                @auth
                    @if(auth()->id() === $post->author_id)
                        <div class="pt-6 border-t border-gray-700">
                            <form action="{{ route('blog.delete', $post->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-2 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200 text-sm md:text-base" onclick="return confirm('Weet je zeker dat je dit bericht wilt verwijderen?')">
                                    <i class="las la-trash-alt"></i>
                                    <span>Verwijderen</span>
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
            <div class="bg-gradient-to-br from-gray-800 to-gray-900 p-5 md:p-8 rounded-xl border border-gray-700">
                <h2 class="text-xl md:text-2xl font-bold text-white mb-6">Reacties</h2>
                
                <form action="{{ route('reaction.store', $post->id) }}" method="POST" class="mb-8">
                    @csrf
                    <div class="mb-4">
                        <textarea name="content" placeholder="Schrijf je reactie..." class="w-full p-3 md:p-4 rounded-lg bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 text-sm md:text-base" rows="4" required style="overflow-wrap: break-word; word-break: break-word;"></textarea>
                    </div>
                    @auth
                        <button type="submit" class="w-full md:w-auto bg-gradient-to-r from-yellow-600 to-yellow-500 text-white px-6 py-3 rounded-lg cursor-pointer hover:from-yellow-700 hover:to-yellow-600 transition-all duration-300 inline-flex items-center justify-center md:justify-start gap-2 font-semibold shadow-lg text-sm md:text-base">
                            <i class="las la-paper-plane"></i> Plaats reactie
                        </button>
                    @else
                        <p class="text-gray-400 text-xs md:text-sm"><a href="{{ route('login') }}" class="text-yellow-400 hover:text-yellow-300">Log in</a> om een reactie te plaatsen</p>
                    @endauth
                </form>
                <div class="space-y-4">
                    @forelse($post->reactions as $reaction)
                        <div class="bg-gray-700 p-4 rounded-lg border border-gray-600 overflow-hidden">
                            <div class="flex items-start justify-between mb-2 gap-2">
                                <div class="flex-1 min-w-0">
                                    <span class="font-semibold text-yellow-400 block text-sm truncate">{{ $reaction->user->name ?? 'Anoniem' }}</span>
                                    <span class="text-gray-500 text-xs block">{{ $reaction->created_at->format('d-m-Y H:i') }}</span>
                                </div>
                                @auth
                                    @if(auth()->id() === $reaction->user_id)
                                        <form action="{{ route('reaction.delete', $reaction->id) }}" method="POST" class="flex-shrink-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-300 cursor-pointer text-sm" onclick="return confirm('Reactie verwijderen?')">
                                                <i class="las la-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                            <p class="text-gray-200 break-words text-sm overflow-hidden" style="word-break: break-word; white-space: normal;">{{ $reaction->content }}</p>
                        </div>
                    @empty
                        <p class="text-gray-400 text-sm">Nog geen reacties. Wees de eerste!</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layout>