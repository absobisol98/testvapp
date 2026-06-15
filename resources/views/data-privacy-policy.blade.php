<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Privacy Policy | Ayala Volunteer System</title>
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
            <h1 class="text-4xl font-bold text-primary mb-4">Data Privacy Policy</h1>
            {{-- <p class="text-xl text-secondary">Protecting Your Information with Care</p> --}}
        </header>

        <main class="bg-white shadow-lg rounded-lg p-8 max-w-4xl mx-auto">
            <section class="mb-8">
                <p>Ayala Foundation, Inc. (the "Foundation") values and respects your privacy. Any personal information you provide to the Foundation will be processed in
                    accordance with the Data Privacy Act of 2012 (Republic Act No. 10173) and its Implementing Rules and Regulations (IRR), as well as the issuances of the National
                    Privacy Commission (NPC).</p>
                    <br>
                <p>Collection and Use:
                        We collect and use your personal data to allow us to include you in the Ayala Corporate Citizenship and Volunteer Program and other related activities and
                        services.
                    </p>
            </section>
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-primary border-b-2 border-secondary pb-2 mb-4">
                    The personal information we require includes:
                </h2>
                <p>As a volunteer organization, we collect necessary personal information to manage your volunteer activities, including:</p>
                <ol class="list-decimal list-inside ml-4 mt-2 space-y-2">
                    <li>Name</li>
                    <li>E-mail</li>
                    <li>Contact Number</li>
                    <li>Age (Range)</li>
                    <li>Company name and cluster</li>
                    <li>Emergency contact details</li>
                    <li>Other personal information deemed necessary to the purposes of collection and processing based on the criteria provided by law. </li>
                </ol>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-primary border-b-2 border-secondary pb-2 mb-4">
                    The Foundation to collect, process, share, store, and use your personal information for the following purposes:
                </h2>
                <ol class="list-decimal list-inside ml-4 mt-2 space-y-2">
                    <li>Inclusion in the Foundation's alumni community; </li>
                    <li>Registration for Foundation events, including event planning, logistics, and sending event-related information and reminders;</li>
                    <li>Creation and management of an alumni database for communication, updates, and future event invitations;</li>
                    <li>Participation in Foundation and/or Ayala Group projects and initiatives; and</li>
                    <li>Other legitimate purposes as required by law or the Foundation's activities. </li>
                </ol>
            </section>

            <section class="mb-8">
                <p>Access to your personal data will be limited to individuals or entities who require it to achieve the purposes outlined above.  The Foundation ensures that your
                    personal information will only be shared with individuals or entities who strictly complies with the data privacy requirements and use the same standards to
                    protect your rights as data subject. </p>
                <br>
                <p>Your personal data will be retained for as long as necessary to fulfill the purposes specified above, in compliance with applicable laws, or until you request its
                    deletion.
                    </p>
                <br>
                <p>As a data subject, you have the right to request access to the personal data you have provided, correct any inaccuracies in your data, withdraw your consent at
                    any time, subject to applicable legal and contractual obligations, and request the deletion or blocking of your personal data by submitting a written request to AFI.</p>
                <br>
                <p>
                    The Foundation will implement reasonable and appropriate measures to protect your personal data. However, you are responsible for ensuring the accuracy and
completeness of the personal information you provide. the Foundation will not be liable for any issues arising from inaccurate, incorrect, or incomplete data
provided by you.
                </p>
                <br>
                <p>For any concerns or inquiries regarding your personal data or to exercise your rights, please contact the Foundation’s Data Protection Officer at
                    <a href="mailto:dpo@ayalafoundation.org" class="text-blue-600 underline">dpo@ayalafoundation.org.</a></p>

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
