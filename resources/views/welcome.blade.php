<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bring Them Home - Missing Children Platform</title>
    {{-- Using CDN for immediate display, but remember to run npm run build for production! --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 antialiased">

    {{-- NAVIGATION BAR --}}
    <nav class="bg-white shadow mb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.656l-6.828-6.828a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>
                    <h1 class="text-2xl font-bold text-blue-600">Bring Them Home</h1>
                </div>
                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            {{-- Check if Admin to show the red link --}}
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="text-red-600 hover:text-red-800 font-bold">Admin Console</a>
                            @endif
                            <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">Log In</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-md">Register to Help</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    {{-- MAIN CONTENT AREA --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- Site Slogan --}}
        <div class="text-center mb-10">
            <h2 class="text-4xl font-extrabold text-gray-900">Every Share is a Hope</h2>
            <p class="mt-2 text-lg text-gray-500">The platform dedicated to reuniting missing children with their families.</p>
        </div>

        {{-- SEARCH BUTTON --}}
        <div class="flex justify-end mb-8">
            <a href="{{ route('search.index') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-full shadow-lg hover:bg-indigo-700 font-bold flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                Advanced Search
            </a>
        </div>

        {{-- SECTION 1: PARENTS SEEKING CHILDREN --}}
        <div class="mb-16">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 border-l-4 border-blue-600 pl-4">Parents Seeking Children</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($parentsSeeking as $post)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <div class="h-64 w-full bg-gray-200 relative">
                            @if($post->upload)
                                <img src="{{ route('file.show', $post->upload->id) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="flex items-center justify-center h-full text-gray-400">No Photo Available</div>
                            @endif
                            <div class="absolute top-0 right-0 m-2">
                                <span class="bg-red-600 text-white text-xs font-bold px-2 py-1 rounded shadow">
                                    {{ $post->status == 'missing' ? 'MISSING' : 'FOUND' }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $post->title }}</h3>
                            <div class="text-sm text-gray-600 mb-4 space-y-1">
                                <p><span class="font-bold text-gray-700">Age:</span> {{ $post->age ?? 'N/A' }}</p>
                                <p><span class="font-bold text-gray-700">Last Seen:</span> {{ $post->location }}</p>
                                <p><span class="font-bold text-gray-700">Posted:</span> {{ $post->created_at->diffForHumans() }}</p>
                            </div>
                            <p class="text-gray-700 text-base mb-4 line-clamp-3">{{ $post->description }}</p>
                            <div class="border-t pt-4">
                                <a href="{{ route('posts.show', $post->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                                    View Details & Provide Tips &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($parentsSeeking->isEmpty()) <p class="text-gray-500">No reports in this category yet.</p> @endif
        </div>

        {{-- SECTION 2: CHILDREN SEEKING FAMILY --}}
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-6 border-l-4 border-green-600 pl-4">Children/Volunteers Seeking Family</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($childrenSeeking as $post)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <div class="h-64 w-full bg-gray-200 relative">
                            @if($post->upload)
                                <img src="{{ route('file.show', $post->upload->id) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="flex items-center justify-center h-full text-gray-400">No Photo Available</div>
                            @endif
                            <div class="absolute top-0 right-0 m-2">
                                <span class="bg-green-600 text-white text-xs font-bold px-2 py-1 rounded shadow">
                                    {{ $post->status == 'missing' ? 'SEARCHING' : 'REUNITED' }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $post->title }}</h3>
                            <div class="text-sm text-gray-600 mb-4 space-y-1">
                                <p><span class="font-bold text-gray-700">Age:</span> {{ $post->age ?? 'N/A' }}</p>
                                <p><span class="font-bold text-gray-700">Last Known Loc:</span> {{ $post->location }}</p>
                                <p><span class="font-bold text-gray-700">Posted:</span> {{ $post->created_at->diffForHumans() }}</p>
                            </div>
                            <p class="text-gray-700 text-base mb-4 line-clamp-3">{{ $post->description }}</p>
                            <div class="border-t pt-4">
                                <a href="{{ route('posts.show', $post->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                                    View Details & Provide Tips &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($childrenSeeking->isEmpty()) <p class="text-gray-500">No reports in this category yet.</p> @endif
        </div>

    </div>

    {{-- FOOTER --}}
    <footer class="bg-gray-800 border-t mt-12 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-400">
            &copy; {{ date('Y') }} Bring Them Home Project. All Rights Reserved.
        </div>
    </footer>
</body>
</html>