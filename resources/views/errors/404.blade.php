@extends('custom.layouts.app')

@section('title', 'Page Not Found')

@section('content')
<main>
    <section class="flex items-center justify-center " style="height: 100vh; background: url('{{ asset('images/ayala.png') }}') no-repeat center center; background-size: cover;">
        <div class="px-4 mx-auto max-w-screen-xl">
            <div class="mx-auto max-w-screen-sm text-center">
                <h1 class="flex items-center justify-center mb-4 text-7xl font-extrabold lg:text-[9xl] text-[#f55e1d]" style="font-size: 182px;">404</h1>
                <h3 class="text-gray-700 sm:text-2xl text-xl font-semibold mb-3 text-2xl md:text-3xl">Not Found</h3>
                <h4 class="text-[#0433ff] text-xl font-bold mb-2">Sorry, we were unable to find that page</h4>
                <p class="font-medium text-gray-600">Uh-oh! Looks like you took a wrong turn. The page you're looking for isn't here. We'll redirect you back to your previous page.</p>
                {{-- <a href="{{route('filament.pages.dashboard')}}" class="inline-flex text-white bg-primary-500 hover:bg-primary-500 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:focus:ring-primary-600 my-4">Back to Dashboard</a> --}}
            </div>
        </div>
    </section>
</main>

<script>
    function goBackTimed() {
        setTimeout(() => {
            window.history.go(-1);
        }, 5000); // 5 seconds delay
    }

    goBackTimed();
</script>
@endsection
