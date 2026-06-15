<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms and Conditions | Ayala Volunteer System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#f55e1d',
                        secondary: '#0433ff'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="container mx-auto px-4 py-12">
        <header class="text-center mb-12">
            <h1 class="text-4xl font-bold text-primary mb-4">Terms and Conditions</h1>
            {{-- <p class="text-xl text-secondary">Protecting Your Information with Care</p> --}}
        </header>

        <main class="bg-white shadow-lg rounded-lg p-8 max-w-4xl mx-auto">
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-primary border-b-2 border-secondary pb-2 mb-4">
                    Introduction
                </h2>
                <p>Welcome to Ayala Volunteer System. By registering and participating as a volunteer, you agree to comply with the terms and conditions outlined below. Please read these terms carefully before proceeding.</p>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-primary border-b-2 border-secondary pb-2 mb-4">
                    Eligibility
                </h2>
                <p>To participate in the volunteer system, you must:</p>
                <ul class="list-disc list-inside ml-4 mt-2 space-y-2">
                    <li>Be at least legal age.</li>
                    <li>Provide accurate and complete registration details.</li>
                    <li>Adhere to the policies and guidelines set by Ayala Foundation Inc.</li>
                </ul>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-primary border-b-2 border-secondary pb-2 mb-4">
                    Volunteer Responsibilities
                </h2>
                <p>As a volunteer, you agree to:</p>
                <ul class="list-disc list-inside ml-4 mt-2 space-y-2">
                    <li>Perform assigned duties to the best of your ability.</li>
                    <li>Follow the instructions and guidelines provided by Ayala Foundation Inc.</li>
                    <li>Regular security audits</li>
                    <li>Compliance with data protection regulations</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-semibold text-primary border-b-2 border-secondary pb-2 mb-4">
                    YLiability and Indemnity
                </h2>
                <p>You have the right to:</p>
                <ul class="list-disc list-inside ml-4 mt-2 space-y-2">
                    <li>Access your personal data</li>
                    <li>Request data correction</li>
                    <li>Request data deletion</li>
                    <li>Withdraw consent for data processing</li>
                </ul>
            </section>

            {{-- <div class="mt-12 text-center">
                <a href="#" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-opacity-90 transition">
                    Contact Us About Data Privacy
                </a>
            </div> --}}
        </main>

        <footer class="text-center mt-8 text-gray-600">
            <p>© {{ date('Y') }} Volunteer System. All Rights Reserved.</p>
            <p class="text-sm">Last Updated: {{ date('F d, Y') }}</p>
        </footer>
    </div>
</body>
</html>
