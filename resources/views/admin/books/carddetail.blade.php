<x-layout.admin-layout>
  <!-- Detail Section -->
  <section class="max-w-7xl mx-auto px-6 py-10 grid grid-cols-1 lg:grid-cols-12 gap-8">

    <!-- Left: Image Gallery -->
    <div class="lg:col-span-5 bg-white rounded-2xl shadow-lg p-6">
      <div class="rounded-xl overflow-hidden shadow-md">
        <img src="{{ $shop->image_url ?? 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=900' }}" alt="{{ $shop->title }} cover" class="w-full h-[480px] object-cover hover:scale-105 transition-transform duration-300">
      </div>

      <!-- Thumbnails -->
      <div class="mt-4 flex items-center gap-3 overflow-x-auto">
        <a href="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=900" target="_blank" class="block rounded-lg overflow-hidden border border-gray-100 shadow-sm">
          <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=200" alt="thumb-1" class="h-20 w-14 object-cover">
        </a>
        <a href="https://images.unsplash.com/photo-1516979187457-637abb4f9353?w=900" target="_blank" class="block rounded-lg overflow-hidden border border-gray-100 shadow-sm">
          <img src="https://images.unsplash.com/photo-1516979187457-637abb4f9353?w=200" alt="thumb-2" class="h-20 w-14 object-cover">
        </a>
        <a href="https://images.unsplash.com/photo-1496104679561-38b51f4f8b0e?w=900" target="_blank" class="block rounded-lg overflow-hidden border border-gray-100 shadow-sm">
          <img src="https://images.unsplash.com/photo-1496104679561-38b51f4f8b0e?w=200" alt="thumb-3" class="h-20 w-14 object-cover">
        </a>
      </div>

      <!-- Attributes -->
      <div class="mt-6 grid grid-cols-2 gap-3 text-sm text-gray-600">
        <div class="bg-gray-50 border border-gray-100 rounded-lg p-3">
          <p class="text-xs text-gray-500">Format</p>
          <p class="font-medium">Hardcover</p>
        </div>
        <div class="bg-gray-50 border border-gray-100 rounded-lg p-3">
          <p class="text-xs text-gray-500">Reading Time</p>
          <p class="font-medium">~8 hrs</p>
        </div>
        <div class="bg-gray-50 border border-gray-100 rounded-lg p-3">
          <p class="text-xs text-gray-500">Language</p>
          <p class="font-medium">English</p>
        </div>
        <div class="bg-gray-50 border border-gray-100 rounded-lg p-3">
          <p class="text-xs text-gray-500">Pages</p>
          <p class="font-medium">320</p>
        </div>
      </div>
    </div>

    <!-- Right: Details & Actions -->
    <div class="lg:col-span-7 bg-white rounded-2xl shadow-lg p-8 flex flex-col justify-between">
      <div>
        <nav class="text-sm text-gray-500 mb-4" aria-label="Breadcrumb">
          <ol class="list-reset flex items-center gap-2">
            <li><a href="#" class="hover:underline">Home</a></li>
            <li> / </li>
            <li><a href="{{ route('admin.books.index') }}" class="hover:underline">Books</a></li>
            <li> / </li>
            <li class="text-gray-700">{{ $shop->title }}</li>
          </ol>
        </nav>

        <h1 class="text-3xl font-bold text-gray-800">{{ $shop->title }}</h1>
        <p class="text-gray-500 mt-1">by <a href="#" class="font-medium hover:underline">{{ $shop->author ?? 'Unknown' }}</a></p>

        <!-- Rating -->
        <div class="mt-4 flex items-center gap-3">
          <div class="flex items-center text-yellow-500">
            <!-- 4.5 stars -->
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.39 2.462a1 1 0 00-.364 1.118l1.287 3.967c.3.922-.755 1.688-1.54 1.118l-3.39-2.462a1 1 0 00-1.176 0l-3.39 2.462c-.785.57-1.84-.196-1.54-1.118l1.287-3.967a1 1 0 00-.364-1.118L2.045 9.393c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69L9.049 2.927z" />
            </svg>
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.39 2.462a1 1 0 00-.364 1.118l1.287 3.967c.3.922-.755 1.688-1.54 1.118l-3.39-2.462a1 1 0 00-1.176 0l-3.39 2.462c-.785.57-1.84-.196-1.54-1.118l1.287-3.967a1 1 0 00-.364-1.118L2.045 9.393c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69L9.049 2.927z" />
            </svg>
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.39 2.462a1 1 0 00-.364 1.118l1.287 3.967c.3.922-.755 1.688-1.54 1.118l-3.39-2.462a1 1 0 00-1.176 0l-3.39 2.462c-.785.57-1.84-.196-1.54-1.118l1.287-3.967a1 1 0 00-.364-1.118L2.045 9.393c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69L9.049 2.927z" />
            </svg>
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.39 2.462a1 1 0 00-.364 1.118l1.287 3.967c.3.922-.755 1.688-1.54 1.118l-3.39-2.462a1 1 0 00-1.176 0l-3.39 2.462c-.785.57-1.84-.196-1.54-1.118l1.287-3.967a1 1 0 00-.364-1.118L2.045 9.393c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69L9.049 2.927z" />
            </svg>
            <svg class="h-5 w-5 text-gray-300" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.39 2.462a1 1 0 00-.364 1.118l1.287 3.967c.3.922-.755 1.688-1.54 1.118l-3.39-2.462a1 1 0 00-1.176 0l-3.39 2.462c-.785.57-1.84-.196-1.54-1.118l1.287-3.967a1 1 0 00-.364-1.118L2.045 9.393c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69L9.049 2.927z" />
            </svg>
          </div>
          <div class="text-sm text-gray-500">({{ $shop->rating ?? '—' }} · reviews)</div>
        </div>

        <!-- Price & Stock -->
        <div class="mt-6 flex items-center gap-4">
          <p class="text-4xl font-extrabold text-yellow-600">${{ number_format($shop->price, 2) }}</p>
          <span class="px-3 py-1 rounded-full bg-{{ $shop->is_active ? 'green' : 'red' }}-50 text-{{ $shop->is_active ? 'green' : 'red' }}-700 text-sm font-medium">{{ $shop->is_active ? 'In stock' : 'Out of stock' }}</span>
        </div>

        <!-- Short details -->
        <div class="mt-6 grid grid-cols-2 gap-4 text-gray-700">
          <div><strong>Genre:</strong> Fiction</div>
          <div><strong>ISBN:</strong> 978-1234567890</div>
          <div><strong>Publisher:</strong> Elite Publications</div>
          <div><strong>Language:</strong> English</div>
        </div>

        <!-- Description -->
        <div class="mt-6 text-gray-600 leading-relaxed">
          <h3 class="font-semibold mb-2">Description</h3>
          <p>{{ $shop->description }}</p>
        </div>
      </div>

      <!-- Actions -->
      <div class="mt-8 flex items-center gap-4">
        <button class="flex-1 bg-yellow-500 text-white py-3 rounded-xl text-lg hover:bg-yellow-600 transition">Buy Now</button>
        <button class="flex-1 border-2 border-yellow-500 text-yellow-600 py-3 rounded-xl text-lg hover:bg-yellow-500 hover:text-white transition">Add to Cart</button>
        <a href="#" class="text-sm text-gray-500 underline">Add to wishlist</a>
      </div>

    </div>

  </section>

  <!-- Mobile sticky actions -->
  <div class="fixed bottom-4 left-1/2 transform -translate-x-1/2 w-[92%] md:hidden">
    <div class="bg-white rounded-xl shadow-lg p-3 flex gap-3">
      <button class="flex-1 bg-yellow-500 text-white py-2 rounded-lg">Buy Now</button>
      <button class="px-4 py-2 rounded-lg border border-gray-200">Add</button>
    </div>
  </div>

</x-layout.admin-layout>