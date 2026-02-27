<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto bg-white border-r border-gray-200">
        <a href="/" class="flex items-center ps-2.5 mb-5 text-2xl font-bold text-indigo-600">
            ColocEasy
        </a>
        <ul class="space-y-2 font-medium">
            
            @if(auth()->user()->colocations->isNotEmpty())
                <li>
                    <a href="{{ route('colocation.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                        <span class="flex-1 ms-3 whitespace-nowrap">Ma Colocation</span>
                    </a>
                </li>
                
            @endif
            @if(auth()->user()->colocations->isEmpty())
                <li class="pt-4 mt-4 border-t border-gray-100">
                    <span class="px-3 text-xs font-semibold text-gray-500 uppercase">Configuration</span>
                </li>
                <li>
                    <a href="{{ route('colocation.create') }}" class="flex items-center p-2 text-indigo-600 rounded-lg hover:bg-indigo-50">
                        <span class="ms-3">Créer une Coloc</span>
                    </a>
                </li>
            @endif

        
        </ul>
    </div>
</aside>