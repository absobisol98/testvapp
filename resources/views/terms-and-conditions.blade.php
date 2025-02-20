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
                        primary: '#FF781E',
                        secondary: '#005096'
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
            <p class="text-xl text-secondary">Protecting Your Information with Care</p>
        </header>git add .

        <main class="bg-white shadow-lg rounded-lg p-8 max-w-4xl mx-auto">
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-primary border-b-2 border-secondary pb-2 mb-4">
                    Information We Collect
                </h2>
                <p>As a volunteer organization, we collect necessary personal information to manage your volunteer activities, including:</p>
                <ul class="list-disc list-inside ml-4 mt-2 space-y-2">
                    <li>Name and contact details</li>
                    <li>Emergency contact information</li>
                    <li>Volunteer skills and preferences</li>
                    <li>Background check details</li>
                </ul>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-primary border-b-2 border-secondary pb-2 mb-4">
                    How We Use Your Data
                </h2>
                <p>We use your personal information solely for:</p>
                <ul class="list-disc list-inside ml-4 mt-2 space-y-2">
                    <li>Volunteer coordination and management</li>
                    <li>Communication about volunteer opportunities</li>
                    <li>Ensuring volunteer safety and support</li>
                    <li>Compliance with legal requirements</li>
                </ul>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-primary border-b-2 border-secondary pb-2 mb-4">
                    Data Protection Measures
                </h2>
                <p>We implement robust security measures to protect your data, including:</p>
                <ul class="list-disc list-inside ml-4 mt-2 space-y-2">
                    <li>Encrypted data storage</li>
                    <li>Limited access to personal information</li>
                    <li>Regular security audits</li>
                    <li>Compliance with data protection regulations</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-semibold text-primary border-b-2 border-secondary pb-2 mb-4">
                    Your Rights
                </h2>
                <p>You have the right to:</p>
                <ul class="list-disc list-inside ml-4 mt-2 space-y-2">
                    <li>Access your personal data</li>
                    <li>Request data correction</li>
                    <li>Request data deletion</li>
                    <li>Withdraw consent for data processing</li>
                </ul>
            </section>

            <div class="mt-12 text-center">
                <a href="#" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-opacity-90 transition">
                    Contact Us About Data Privacy
                </a>
            </div>
        </main>

        <footer class="text-center mt-8 text-gray-600">
            <p>© {{ date('Y') }} Volunteer System. All Rights Reserved.</p>
            <p class="text-sm">Last Updated: {{ date('F d, Y') }}</p>
        </footer>
    </div>
</body>
</html>
