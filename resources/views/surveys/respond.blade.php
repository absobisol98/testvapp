@extends('custom.layouts.app')

@section('content')
<div class="bg-gray-50 pt-[5%]">
    <div class="min-h-screen md:h-auto flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl w-full bg-white rounded-lg shadow-lg p-8">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <img src="{{ asset('img/logo-colored.png') }}" alt="Ayala Logo" class="mx-auto h-16 mb-6">
                <h1 class="text-3xl font-bold text-secondary mb-2 capitalize">{{ $survey->title }}</h1>
                <p class="text-gray-600 mb-4">{{ $survey->description }}</p>
            </div>

            @if($survey->is_anonymous)
                <div class="bg-blue-50 border-l-4 border-secondary p-4 mb-6 rounded">
                    <p class="text-gray-700">
                        <span class="font-semibold">Note:</span>
                        This survey is anonymous. Your responses will not be linked to your identity.
                    </p>
                </div>
            @endif

            <form method="POST" action="{{ route('survey.submit', $survey) }}" class="space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                @foreach($survey->questions as $index => $question)
                    <div class="bg-gray-50 p-6 rounded-lg mb-6">
                        <label class="block text-lg font-medium text-gray-900 mb-4">
                            {{ $question['question'] }}
                        </label>

                        @switch($question['type'])
                            @case('rating')
                                <div class="flex gap-6 justify-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="flex flex-col items-center cursor-pointer group">
                                            <input type="radio"
                                                   name="question_{{ $index }}"
                                                   value="{{ $i }}"
                                                   class="hidden peer"
                                                   required>
                                            <div class="w-12 h-12 flex items-center justify-center rounded-full
                                                        border-2 border-gray-300 text-gray-500
                                                        peer-checked:border-secondary peer-checked:bg-secondary peer-checked:text-white
                                                        group-hover:border-secondary transition-all duration-200">
                                                {{ $i }}
                                            </div>
                                        </label>
                                    @endfor
                                </div>
                                @break

                            @case('multiple_choice')
                                <div class="space-y-3">
                                    @foreach($question['options'] as $option)
                                        <label class="flex items-center p-3 rounded-lg border border-gray-200
                                                    cursor-pointer hover:bg-gray-100 transition-all duration-200">
                                            <input type="radio"
                                                   name="question_{{ $index }}"
                                                   value="{{ $option['option'] }}"
                                                   class="w-4 h-4 text-secondary"
                                                   required>
                                            <span class="ml-3">{{ $option['option'] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @break

                            @default
                                <textarea name="question_{{ $index }}"
                                         rows="4"
                                         class="w-full border-gray-300 rounded-lg focus:ring-secondary focus:border-secondary
                                                p-4 transition-all duration-200"
                                         placeholder="Enter your response here..."
                                         required></textarea>
                        @endswitch
                    </div>
                @endforeach

                <div class="flex justify-center mt-8">
                    <button type="submit"
                            class="inline-flex items-center justify-center px-8 py-3 bg-[#005096]
                                   text-white font-medium rounded-lg hover:bg-opacity-90
                                   transition duration-300 transform hover:scale-105">
                        Submit Response
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
@endsection

@push('scripts')
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#FF781E',
                    secondary: '#005096'
                }
            }
        }
    }
</script>
@endpush
