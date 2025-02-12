<!DOCTYPE html>
<html>
<head>
    <title>Survey Response</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">{{ $survey->title }}</h1>
        <p class="mb-6">{{ $survey->description }}</p>

        @if($survey->is_anonymous)
            <div class="bg-blue-50 p-4 mb-6 rounded">
                <p>This survey is anonymous. Your responses will not be linked to your identity.</p>
            </div>
        @endif

        <form method="POST" action="{{ route('survey.submit', $survey) }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            @foreach($survey->questions as $index => $question)
                <div class="mb-6">
                    <label class="block font-medium mb-2">{{ $question['question'] }}</label>

                    @switch($question['type'])
                        @case('rating')
                            <div class="flex gap-4">
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="flex items-center">
                                        <input type="radio"
                                               name="question_{{ $index }}"
                                               value="{{ $i }}"
                                               class="mr-2"
                                               required>
                                        {{ $i }}
                                    </label>
                                @endfor
                            </div>
                            @break

                        @case('multiple_choice')
                            @foreach($question['options'] as $option)
                                <label class="block mb-2">
                                    <input type="radio"
                                           name="question_{{ $index }}"
                                           value="{{ $option['option'] }}"
                                           class="mr-2"
                                           required>
                                    {{ $option['option'] }}
                                </label>
                            @endforeach
                            @break

                        @default
                            <textarea name="question_{{ $index }}"
                                     rows="3"
                                     class="w-full border rounded p-2"
                                     required></textarea>
                    @endswitch
                </div>
            @endforeach

            <button type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Submit Response
            </button>
        </form>
    </div>
</body>
</html>
