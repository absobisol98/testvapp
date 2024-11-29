<x-filament-panels::page id="volunteer-dashboard">
    <style>
        /* For Volunteer Dashboard Container(Start) */
        .fi-main {
            margin: 0px !important;
            padding: 0px 0px !important;
            margin-top: 0px !important;
            margin-bottom: 0px !important;
            padding-top: 0px !important;
            padding-bottom: 0px !important;
            border-radius: 0px !important;
            max-width: 100% !important;
        }

        .fi-page section {
            padding: 0px 0px 32px 0px !important;
        }

        /* For Volunteer Dashboard Container(End) */


        /* For StatsOverview(Start) */
        #dashboard-container {
            width: 100%;
        }

        #dashboard-main {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            min-height: 583px;
        }

        #stat-content {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2rem;
            padding: 2rem;
            z-index: 1;
        }

        #stat-header {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
        }

        #stat-header-text {
            font-size: 30px;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #FFFFFF;
        }

        #stat-header-title {
            font-size: 55px;
            font-weight: 700;
            color: #FFFFFF;
        }

        #stat-image-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            background-color: #FFFFFF;
            padding: 1rem;
        }

        #stat-image {
            width: 100%;
            height: 100%;
            min-height: 160px;
            max-width: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-radius: 20px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        #stat-details {
            width: 100%;
        }

        #stat-title {
            font-size: 28px;
            font-weight: 400;
            color: #03498D;
            margin-bottom: 0.5rem;
        }

        #stat-date {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: flex-start;
            font-size: 14px;
            font-weight: 400;
            color: #000000;
            margin-bottom: 1.5rem;
            gap: 1rem;
        }

        #stat-date-bold {
            font-weight: 600;
        }

        .button-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .button-container a {
            width: 100%;
            display: block;
        }

        .button-checkin {
            height: 40px;
            width: 100%;
            background-color: #FF781E;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem;
            transition: background-color 0.3s;
            color: #FFFFFF;
        }

        .button-checkin:hover {
            background-color: #FF9141;
        }

        .button-cancel {
            height: 40px;
            width: 100%;
            background-color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem;
            transition: background-color 0.3s;
        }

        .button-cancel:hover {
            background-color: #f1f1f1;
        }

        #stat-footer {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 1rem;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 2rem;
        }

        .stat-number {
            font-size: 40px;
            font-weight: 700;
            color: #F55E1D;
        }

        .stat-label {
            font-size: 14px;
            font-weight: 700;
            color: #03498D;
        }

        #stat-background {
            width: 100%;
            height: 100%;
            display: grid;
            grid-template-columns: 3fr 2fr;
            position: absolute;
            z-index: 0;
        }

        #background-left {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        #background-left-gradient {
            width: 100%;
            height: 100%;
            position: absolute;
            inset: 0;
            background: linear-gradient(to top right, #03498D, #03498D);
            clip-path: polygon(100% -20%, 100% 100%, 50% 100%);
            z-index: 2;
        }

        #background-left-overlay {
            width: 100%;
            height: 100%;
            position: absolute;
            background: linear-gradient(to right, #03488d8a, #03488d8a, #03488d8a);
            z-index: 1;
        }

        #background-left-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 0;
        }

        #background-right {
            background-color: #03498D;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #bottom-bar {
            width: 95%;
            height: 30px;
            background-color: #F55E1D;
            margin-left: auto;
        }

        /* For StatsOverview(End) */

        /* For Ads Section(Start) */
        #adsSection {
            width: 100%;
            height: 100%;
            min-height: 170px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background-color: #F9F9F9;
            padding: 12px;
            /* p-3 equivalent to 12px padding */
        }

        /* For Ads Section(End) */


        /* For Upcoming Opportunity(Start) */
        /* Responsive breakpoints */
        @media (min-width: 768px) {
            #opportunitiesTitle p {
                font-size: 32px;
            }

            .opportunityItem {
                flex-direction: row;
            }

            .opportunityImage {
                width: 200px;
                height: 140px;
            }
        }

        /* Main Section */
        #opportunitiesSection {
            width: 100%;
        }

        /* Header Section */
        #opportunitiesHeader {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            font-weight: 400;
        }

        #opportunitiesTitle p {
            font-size: 28px;
            color: #03498D;
            font-weight: 700;
        }

        #opportunitiesActions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        #viewLink {
            font-size: 20px;
            font-weight: 400;
            text-decoration: none;
        }

        #viewLink:hover {
            text-decoration: underline;
        }

        /* Tabs */
        #listTab,
        #calendarTab {
            width: 32px;
            height: 32px;
            background: none;
            border: none;
            cursor: pointer;
            transition: stroke 0.3s ease;
        }

        #listTab svg path,
        #calendarTab svg path {
            stroke: #000000;
        }

        #listTab:hover svg path,
        #calendarTab:hover svg path {
            stroke: #FF781E;
        }

        #listTab.active svg path,
        #calendarTab.active svg path {
            stroke: #FF9141;
        }

        /* Divider */
        #headerDivider {
            width: 100%;
            height: 1px;
            background-color: #DFDFDF;
            margin: 16px 0;
        }

        /* Opportunity List */
        #opportunityList {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 16px;
            padding: 16px;
            color: #000000;
            transition: all 0.3s ease-in-out;
        }

        .opportunityItem {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .opportunityImage img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .opportunityDetails {
            width: 100%;
        }

        .opportunityTitle {
            font-size: 28px;
            color: #03498D;
            cursor: pointer;
        }

        .opportunityLocation {
            font-size: 18px;
            margin-bottom: 12px;
        }

        .opportunityInfo {
            display: flex;
            align-items: center;
            justify-content: start;
            gap: 16px;
        }

        .opportunityShifts div {
            display: flex;
            align-items: center;
            justify-content: start;
            gap: 16px;
        }

        .opportunityInfo p {
            font-size: 14px;
            font-weight: 400;
        }

        .opportunityDate p,
        .opportunityShifts span {
            font-weight: 600;
        }

        .checkInButton {
            width: 200px;
        }

        .checkInButton div {
            height: 48px;
            width: 100%;
            background-color: #FF781E;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s ease-in-out;
        }

        .checkInButton div:hover {
            background-color: #FF9141;
        }

        .checkInButton p {
            font-size: 18px;
            font-weight: 400;
            color: white;
        }

        /* Calendar Section */
        #opportunityCalendar {
            width: 100%;
            padding: 16px;
            display: flex;
        }

        #opportunityCalendar div {
            width: 100%;
        }

        /* Divider Between Opportunities */
        .opportunityDivider {
            width: 100%;
            height: 1px;
            background-color: #DFDFDF;
            margin: 16px 0;
        }

        /* For Upcoming Opportunity(End) */


        /* For Recent Opportunity(Start) */
        #recentOpportunitiesHeader {
            display: flex;
            align-items: center;
            justify-content: start;
            gap: 16px;
            margin-bottom: 16px;
        }

        #opportunitiesTags {
            width: 100%;
            max-width: 30%;
            display: flex;
            align-items: center;
            justify-content: start;
            gap: 8px;
            padding: 8px 16px;
            color: #000000;
            font-size: 12px;
            font-weight: 400;
            background: #F5F5F5;
            border-radius: 20px;
        }

        #opportunitiesTags .tag {
            width: fit-content;
            padding: 4px 8px;
            background: #DADADA;
            border-radius: 10px;
        }

        .tagRemove:hover {
            cursor: pointer;
            font-weight: 700;
        }

        .opportunitiesGrid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 32px;
            color: #000000;
        }

        /* Individual Opportunity Card */
        .opportunityCard {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* Tailwind `shadow-md` */
            height: 100%;
        }

        /* Card Header */
        .cardHeader {
            position: relative;
            height: 350px;
            display: flex;
            align-items: flex-end;
            justify-content: flex-start;
            padding: 16px;
            /* Tailwind `p-4` = 16px */
            background: linear-gradient(to top, rgba(0, 0, 0, 1), rgba(0, 0, 0, 0));
        }

        .logoOverlay {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: flex-end;
            justify-content: flex-start;
        }

        .logoImage {
            width: 40%;
        }

        /* Card Content */
        .cardContent {
            width: 100%;
            padding: 16px;
            /* Tailwind `p-4` */
            font-size: 14px;
        }

        /* Program and Date */
        .programDate {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            /* Tailwind `gap-4` = 16px */
            font-size: 14px;
            font-weight: 400;
        }

        .programTag {
            padding: 4px 8px;
            /* Tailwind `py-1 px-2` */
            background-color: #F55E1D;
            color: #FFFFFF;
        }

        .dateTag {
            font-weight: 600;
        }

        /* Opportunity Title */
        .opportunityTitle {
            font-size: 25px;
            font-weight: 700;
            color: #03498D;
            margin-top: 12px;
            margin-bottom: 4px;
            line-height: 1;
            cursor: pointer;
        }

        /* Location */
        .location {
            font-weight: 400;
            margin-bottom: 12px;
            /* Tailwind `mb-3` */
        }

        /* Bold Text */
        .boldText {
            font-weight: 600;
        }

        /* Shifts */
        .shifts {
            display: flex;
            flex-direction: column;
            gap: 4px;
            /* Tailwind `gap-4` */
        }

        /* Buttons */
        .cardButtons {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-top: 16px;
            font-size: 16px;
        }

        .buttonContent {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 40px;
            width: 100% !important;
            padding: 8px;
            color: #FFFFFF;
            cursor: pointer;
        }

        .viewDetailsBtn,
        .signupBtn {
            width: 100%;
        }

        /* Blue Button */
        .blueButton {
            background-color: #005096;
        }

        .blueButton:hover {
            background-color: #1A67B1;
        }

        /* Orange Button */
        .orangeButton {
            background-color: #FF781E;
        }

        .orangeButton:hover {
            background-color: #FF9141;
        }









        .recentOpportunityModalContainer {
            width: 100%;
            height: auto;
        }

        .recentOpportunityModalContainer .modal {
            position: fixed;
            inset: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 50;
            background-color: rgba(0, 0, 0, 0.5);
            transition: opacity 0.3s ease;
        }

        .recentOpportunityModalContainer .modal.hidden {
            display: none;
        }

        .recentOpportunityModalContainer .modal-header {
            display: flex;
            align-items: center;
            justify-content: end;
        }

        .recentOpportunityModalContainer .content {
            margin: 16px 0px;
        }

        .recentOpportunityModalContainer .modal-content {
            color: #000000;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 80%;
            width: 100%;
            padding: 2rem;
            transform: scale(0.95);
            transition: transform 0.3s ease, opacity 0.3s ease;
            opacity: 0;
        }

        .recentOpportunityModalContainer .modal-content.visible {
            transform: scale(1);
            opacity: 1;
        }

        .recentOpportunityModalContainer .modal-close-button {
            font-size: 1.5rem;
            font-weight: 600;
            color: #000000;
            background: none;
            border: none;
            cursor: pointer;
        }

        .recentOpportunityModalContainer .modal-close-button:hover {
            background-color: #f5f5f5;
        }

        .recentOpportunityModalContainer .modal-content-container {
            max-height: 80vh;
            overflow-y: auto;
            margin-bottom: 1rem;
        }

        .recentOpportunityModalContainer .flex-center {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .recentOpportunityModalContainer .flex-column {
            display: flex;
            flex-direction: column;
        }

        .recentOpportunityModalContainer .flex-row {
            display: flex;
            flex-direction: row;
        }

        .recentOpportunityModalContainer .image-container {
            height: 500px;
            width: 100%;
            display: flex;
            gap: 1rem;
            background-size: cover;
            background-position: center;
            background-image: url('/path/to/your/image.jpg');
        }

        .recentOpportunityModalContainer .gradient-overlay {
            height: 100%;
            width: 100%;
            display: flex;
            align-items: flex-end;
            justify-content: flex-start;
            padding: 2rem;
            background: linear-gradient(to top, black, transparent);
        }

        .recentOpportunityModalContainer .logo {
            width: 30%;
        }

        .recentOpportunityModalContainer .program-badge {
            padding: 0.5rem 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: #F55E1D;
            color: #ffffff;
            font-size: 1rem;
            font-weight: 400;
        }

        .recentOpportunityModalContainer .title {
            font-size: 2.25rem;
            font-weight: 400;
            color: #03498D;
        }

        .recentOpportunityModalContainer .location {
            font-size: 1.5rem;
            font-weight: 400;
        }

        .recentOpportunityModalContainer .description {
            font-size: 1rem;
            font-weight: 400;
            text-align: justify;
            margin: 1rem 0;
        }

        .recentOpportunityModalContainer .containersh {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .recentOpportunityModalContainer .info-container {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            font-size: 1rem;
            font-weight: 400;
        }

        .recentOpportunityModalContainer .info-container .info-title {
            font-weight: 600;
        }

        .recentOpportunityModalContainer .buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .recentOpportunityModalContainer .button {
            height: 53px;
            width: 229px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem;
            font-size: 1rem;
            color: #ffffff;
            cursor: pointer;
        }

        .recentOpportunityModalContainer .button.signup {
            background-color: #FF781E;
        }

        .recentOpportunityModalContainer .button.signup:hover {
            background-color: #FF9141;
        }

        .recentOpportunityModalContainer .button.favorite {
            background-color: #005096;
        }

        .recentOpportunityModalContainer .button.favorite:hover {
            background-color: #1A67B1;
        }

        .recentOpportunityModalContainer .qr-code {
            max-width: 283px;
        }

        /* For Recent Opportunity(End) */
    </style>

    <div class="w-full flex flex-col items-center justify-between gap-8">
        {{-- STATS OVERVIEW --}}
        <div class="w-full">
            <div id="dashboard-container">
                <div id="dashboard-main">
                    <!-- Stat Content -->
                    <div id="stat-content">
                        <div id="stat-header">
                            <div>
                                <p id="stat-header-text">Welcome Volunteer</p>
                                <p id="stat-header-title">Your involvement <br> is important to us!</p>
                            </div>

                            <div id="stat-image-container">
                                <div id="stat-image">
                                    <img src="{{ asset('img/ayala-foundation-bg.jpg') }}" alt=""
                                        style="width: 100%; height: 100%; object-fit: cover;">
                                </div>

                                <div id="stat-details">
                                    <p id="stat-title">{{ $opportunity->title }}</p>
                                    <div id="stat-date">
                                        <div>
                                            <p id="stat-date-bold">DATE:
                                                {{ \Carbon\Carbon::parse($opportunity->start_date)->format('M-d-Y') }}
                                            </p>
                                        </div>
                                        <div>
                                            @foreach ($opportunity->slots as $index => $slot)
                                                @if ($index == 0)
                                                    <p>
                                                        <span id="stat-date-bold">BATCH {{ $index + 1 }}:</span>
                                                        {{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }}
                                                        -
                                                        {{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}
                                                    </p>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="button-container">
                                        <a href="">
                                            <div class="button-checkin">
                                                <p>CHECK-IN</p>
                                            </div>
                                        </a>
                                        <a href="">
                                            <div class="button-cancel">
                                                <p>CANCEL</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="stat-footer">
                            <div class="stat-item">
                                <p class="stat-number">{{ $totalUpcoming }}</p>
                                <p class="stat-label">UPCOMING</p>
                            </div>
                            <div class="stat-item">
                                <p class="stat-number">{{ $totalCertificates }}</p>
                                <p class="stat-label">CERTIFICATES</p>
                            </div>
                            <div class="stat-item">
                                <p class="stat-number">{{ $totalApprovedHrs }}</p>
                                <p class="stat-label">APPROVED HRS</p>
                            </div>
                            <div class="stat-item">
                                <p class="stat-number">{{ $totalRemainingHrs }}</p>
                                <p class="stat-label">REMAINING HRS</p>
                            </div>
                            <div class="stat-item">
                                <p class="stat-number">{{ $totalCancelled }}</p>
                                <p class="stat-label">CANCELLED</p>
                            </div>
                        </div>
                    </div>

                    <!-- Background -->
                    <div id="stat-background">
                        <div id="background-left">
                            <div id="background-left-gradient"></div>
                            <div id="background-left-overlay"></div>
                            <img id="background-left-image" src="{{ asset('img/ayala-foundation-bg-2.jpg') }}"
                                alt="">
                        </div>
                        <div id="background-right"></div>
                    </div>
                </div>

                <div id="bottom-bar"></div>
            </div>
        </div>

        {{-- ADS SECTION --}}
        <div class="w-full px-8">
            <div id="adsSection">
                <p style="font-size: 16px; font-weight: 400; color: #000000;">ADS SECTION</p>
            </div>
        </div>

        {{-- UPCOMING OPPORTUNITY --}}
        <div class="w-full px-8">
            <div id="opportunitiesSection">
                <div id="opportunitiesHeader">
                    <div id="opportunitiesTitle">
                        <p>Upcoming Opportunity</p>
                    </div>

                    <div id="opportunitiesActions">
                        <a id="viewLink" href="">VIEW</a>

                        <button id="listTab" class="active" onclick="changeTab('list')">
                            @include('custom.icons.landing-page-icons', ['icon' => 'list-32'])
                        </button>

                        <button id="calendarTab" onclick="changeTab('calendar')">
                            @include('custom.icons.landing-page-icons', ['icon' => 'calendar-32'])
                        </button>
                    </div>
                </div>

                <div id="headerDivider"></div>

                <div id="opportunityList">
                    @foreach ($upcomingOpportunities as $index => $opportunity)
                        <div class="opportunityItem">
                            <div class="opportunityImage">
                                <img src="{{ asset('img/ayala-foundation-bg.jpg') }}" alt="">
                            </div>

                            <div class="opportunityDetails">
                                <a href="" class="opportunityTitle">{{ $opportunity->title }}</a>
                                <p class="opportunityLocation">Zoom Webinar Online, {{ $opportunity->location }}</p>

                                <div class="opportunityInfo">
                                    <div class="opportunityDate">
                                        <p>DATE: {{ \Carbon\Carbon::parse($opportunity->start_date)->format('M-d-Y') }}
                                        </p>
                                        <p>{{ \Carbon\Carbon::parse($opportunity->start_date)->format('g:i A') }} -
                                            {{ \Carbon\Carbon::parse($opportunity->end_date)->format('g:i A') }}</p>
                                    </div>
                                    <div class="opportunityShifts">
                                        <p><span>SHIFTS:</span> Listen attentively and engage actively in the session
                                        </p>
                                        <div>
                                            @foreach ($opportunity->slots as $index => $slot)
                                                <p><span>BATCH {{ $index + 1 }}:</span>
                                                    {{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }} -
                                                    {{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}</p>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="checkInButton">
                                <a href="">
                                    <div>
                                        <p>CHECK-IN</p>
                                    </div>
                                </a>
                            </div>
                        </div>

                        @if (!$loop->last)
                            <div class="opportunityDivider"></div>
                        @endif
                    @endforeach
                </div>

                <div id="opportunityCalendar">
                    <div>
                        @livewire(\App\Filament\Widgets\CalendarWidget::class)
                    </div>
                </div>
            </div>

            <script>
                function changeTab(type) {
                    const opportunityList = document.getElementById("opportunityList");
                    const opportunityCalendar = document.getElementById("opportunityCalendar");
                    const listTab = document.getElementById("listTab");
                    const calendarTab = document.getElementById("calendarTab");

                    if (type === "list") {
                        opportunityList.style.display = "flex";
                        opportunityCalendar.style.display = "none";
                        listTab.classList.add("active");
                        calendarTab.classList.remove("active");
                    } else if (type === "calendar") {
                        opportunityList.style.display = "none";
                        opportunityCalendar.style.display = "flex";
                        calendarTab.classList.add("active");
                        listTab.classList.remove("active");
                    }
                }

                // Initially hide the calendar
                setTimeout(() => {
                    document.getElementById("opportunityCalendar").style.display = "none";
                }, 2000);
            </script>
        </div>

        {{-- RECENT OPPORTUNITIES --}}
        <div class="w-full px-8">
            <div id="recentOpportunitiesSection">
                <div id="recentOpportunitiesHeader" class="headerContainer">
                    <div id="opportunitiesTitle" class="titleContainer">
                        <p>Recent Opportunities</p>
                    </div>

                    <div id="opportunitiesTags" class="tagsContainer">
                        @foreach ($tags as $tag)
                            <div class="tag">
                                <p>{{ \Illuminate\Support\Str::upper($tag->name) }}
                                    <span class="tagRemove">X</span>
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div id="recentOpportunitiesCards" class="opportunitiesGrid">
                    @foreach ($recentOpportunities as $opportunity)
                        <div id="opportunityCard-{{ $opportunity->id }}" class="opportunityCard">
                            <!-- Card Header -->
                            <div class="cardHeader"
                                style="background: url('{{ asset('img/ayala-foundation-bg.jpg') }}') no-repeat center center; background-size: cover;">
                                <div class="logoOverlay">
                                    <img class="logoImage" src="{{ asset('img/logo-colored.png') }}" alt="">
                                </div>
                            </div>

                            <!-- Card Content -->
                            <div class="cardContent">
                                <!-- Program and Date -->
                                <div class="programDate">
                                    <div class="programTag">
                                        <p>{{ \Illuminate\Support\Str::upper($opportunity->program->name) }}</p>
                                    </div>
                                    <div class="dateTag">
                                        <p>{{ \Carbon\Carbon::parse($opportunity->start_date)->format('M-d-Y') }}</p>
                                    </div>
                                </div>

                                <!-- Title -->
                                <p class="opportunityTitle">
                                    {{ \Illuminate\Support\Str::limit($opportunity->title, 22) }}
                                </p>

                                <!-- Location -->
                                <p class="location">Zoom Webinar Online, {{ $opportunity->location }}</p>

                                <!-- Shifts -->
                                <p><span class="boldText">SHIFTS:</span> Listen attentively and engage actively in the
                                    session</p>
                                <div class="shifts">
                                    @foreach ($opportunity->slots as $index => $slot)
                                        <p><span class="boldText">BATCH {{ $index + 1 }}:</span>
                                            {{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }} -
                                            {{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}
                                        </p>
                                    @endforeach
                                </div>

                                <!-- Buttons -->
                                <div class="cardButtons">
                                    <button id="viewDetailsBtn-{{ $opportunity->id }}" class="viewDetailsBtn">
                                        <div class="buttonContent blueButton">
                                            <p>VIEW DETAILS</p>
                                        </div>
                                    </button>
                                    <a href="#" class="signupBtn">
                                        <div class="buttonContent orangeButton">
                                            <p>SIGN UP</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @foreach ($recentOpportunities as $opportunity)
                    <div class="recentOpportunityModalContainer">
                        <div id="featuredImageModal{{ $opportunity->id }}" class="modal hidden">
                            <!-- Modal Content -->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button id="closeModal{{ $opportunity->id }}" class="modal-close-button">
                                        @include('custom.icons.landing-page-icons', ['icon' => 'close-25'])
                                    </button>
                                </div>

                                <div class="modal-content-container">
                                    <div class="flex-column">
                                        <div class="image-container"
                                            style="background-image: url('{{ asset('img/ayala-foundation-bg.jpg') }}');">
                                            <div class="gradient-overlay">
                                                <img class="logo" src="{{ asset('img/logo-colored.png') }}"
                                                    alt="Logo">
                                            </div>
                                        </div>

                                        <div class="content">
                                            <div class="program-badge">
                                                <p>{{ \Illuminate\Support\Str::upper($opportunity->program->name) }}
                                                </p>
                                            </div>

                                            <p class="title">{{ $opportunity->title }}</p>
                                            <p class="location">Zoom Webinar Online, {{ $opportunity->location }}</p>

                                            <div class="description">
                                                {!! $opportunity->description !!}
                                            </div>

                                            <div class="containersh">
                                                <div>
                                                    <div class="info-container">
                                                        <p class="info-title">DATE:
                                                            {{ \Carbon\Carbon::parse($opportunity->start_date)->format('M-d-Y') }}
                                                            |
                                                            {{ \Carbon\Carbon::parse($opportunity->start_date)->format('g:i A') }}
                                                            -
                                                            {{ \Carbon\Carbon::parse($opportunity->end_date)->format('g:i A') }}
                                                        </p>
                                                        <p><span class="info-title">SHIFTS:</span> Listen attentively and
                                                            engage actively in the session</p>
        
                                                        @foreach ($opportunity->slots as $index => $slot)
                                                            <p><span class="info-title">BATCH {{ $index + 1 }}:</span>
                                                                {{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }}
                                                                - {{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}
                                                            </p>
                                                        @endforeach
                                                    </div>
        
                                                    <div class="buttons">
                                                        <a href="">
                                                            <div class="button signup">
                                                                <p>SIGN UP</p>
                                                            </div>
                                                        </a>
        
                                                        <a href="">
                                                            <div class="button favorite">
                                                                <p>FAVORITE</p>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
    
                                                <div class="qr-code">
                                                    <img src="{{ asset('img/qr.png') }}" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Modal Scripts --}}
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        // Parse opportunities from Laravel
                        const opportunities = @json($recentOpportunities);
                
                        // Loop through each opportunity
                        opportunities.forEach(opportunity => {
                            const viewDetailsBtn = document.getElementById(`viewDetailsBtn-${opportunity.id}`);
                            const modal = document.getElementById(`featuredImageModal${opportunity.id}`);
                            const modalContent = modal.querySelector('.modal-content');
                            const closeModal = document.getElementById(`closeModal${opportunity.id}`);
                
                            if (viewDetailsBtn && modal && closeModal) {
                                // Open modal
                                viewDetailsBtn.addEventListener('click', function () {
                                    modal.classList.remove('hidden'); // Show modal
                                    setTimeout(() => {
                                        modal.classList.remove('opacity-0'); // Fade in background
                                        modalContent.classList.remove('scale-95', 'opacity-0'); // Fade in content
                                        modalContent.style.opacity = "1"; // Ensure full opacity
                                    }, 10); // Small delay for smooth transition
                                });
                
                                // Close modal when clicking background or close button
                                modal.addEventListener('click', function (event) {
                                    if (event.target === modal || event.target.closest(`#closeModal${opportunity.id}`)) {
                                        closeModalFunction(modal, modalContent);
                                    }
                                });
                
                                // Close modal directly when clicking the close button
                                closeModal.addEventListener('click', function () {
                                    closeModalFunction(modal, modalContent);
                                });
                            }
                        });
                
                        // Function to close the modal
                        function closeModalFunction(modal, modalContent) {
                            modalContent.classList.add('scale-95', 'opacity-0'); // Fade out content
                            modalContent.style.opacity = "0"; // Reset to fully transparent
                            modal.classList.add('opacity-0'); // Fade out background
                            setTimeout(() => {
                                modal.classList.add('hidden'); // Hide modal after animation
                            }, 300); // Delay matches CSS transition duration
                        }
                    });
                </script>                
                

            </div>
        </div>
    </div>
</x-filament-panels::page>
