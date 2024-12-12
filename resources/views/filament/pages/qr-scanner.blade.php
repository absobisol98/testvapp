<x-filament-panels::page>
    <div id="video-container" hidden>
        <video id="preview" controls></video>
        <div class="overlay">
            <div class="qr-scanner">
                <div class="scanner-line"></div>
            </div>
        </div>
    </div>
    <button id="btn_start" onclick="start_scan()">
        <div class="w-full bg-primary-500 flex items-center justify-center p-2 hover:bg-primary-400">
            <p class="font-[400] text-[18px] text-white">Start scanning</p>
        </div>
    </button>
    <button id="btn_stop" onclick="stop_scan()" hidden>
        <div class="w-full bg-danger-500 flex items-center justify-center p-2 hover:bg-danger-400">
            <p class="font-[400] text-[18px] text-white">Stop scanning</p>
        </div>
    </button>
    <div class="blur-overlay">
        <div role="status">
            <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-primary-600"
                 viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                    fill="currentColor"/>
                <path
                    d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                    fill="currentFill"/>
            </svg>
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script src="https://unpkg.com/axios@1.6.7/dist/axios.min.js"></script>
    <script type="text/javascript">
        let opts = {
            // Whether to scan continuously for QR codes. If false, use scanner.scan() to manually scan.
            // If true, the scanner emits the "scan" event when a QR code is scanned. Default true.
            continuous: true,

            // The HTML element to use for the camera's video preview. Must be a <video> element.
            // When the camera is active, this element will have the "active" CSS class, otherwise,
            // it will have the "inactive" class. By default, an invisible element will be created to
            // host the video.
            video: document.getElementById('preview'),

            // Whether to horizontally mirror the video preview. This is helpful when trying to
            // scan a QR code with a user-facing camera. Default true.
            mirror: false,

            // Whether to include the scanned image data as part of the scan result. See the "scan" event
            // for image format details. Default false.
            captureImage: true,

            // Only applies to continuous mode. Whether to actively scan when the tab is not active.
            // When false, this reduces CPU usage when the tab is not active. Default true.
            backgroundScan: true,

            // Only applies to continuous mode. The period, in milliseconds, before the same QR code
            // will be recognized in succession. Default 5000 (5 seconds).
            refractoryPeriod: 5000,

            // Only applies to continuous mode. The period, in rendered frames, between scans. A lower scan period
            // increases CPU usage but makes scan response faster. Default 1 (i.e. analyze every frame).
            scanPeriod: 1
        };
        let scanner = new Instascan.Scanner(opts);
        scanner.addListener('scan', function (content) {
            console.log(content);

            if (content) {
                document.querySelector('.blur-overlay').style.display = 'flex';
                scan_qr(content);

            }
        });

        function stop_scan() {
            document.getElementById('btn_start').style.display = 'block';
            document.getElementById('btn_stop').style.display = 'none';
            document.getElementById('video-container').style.display = 'none';
            scanner.stop();
        }

        function start_scan() {
            document.querySelector('.blur-overlay').style.display = 'flex';
            Instascan.Camera.getCameras().then(function (cameras) {
                //If a camera is detected
                if (cameras.length > 0) {
                    document.getElementById('btn_stop').style.display = 'block';
                    document.getElementById('btn_start').style.display = 'none';
                    document.getElementById('video-container').style.display = 'block';
                    document.querySelector('.blur-overlay').style.display = 'none';

                    //If the user has a rear/back camera
                    if (cameras[1]) {
                        //use that by default
                        scanner.start(cameras[1]);
                    } else {
                        //else use front camera
                        scanner.start(cameras[0]);
                    }
                } else {
                    //if no cameras are detected give error
                    document.querySelector('.blur-overlay').style.display = 'none';
                    console.error('No cameras found.');
                }
            }).catch(function (e) {
                alert('Invalid QR');
                console.error(e);
            });
        }

        // Want to use async/await? Add the `async` keyword to your outer function/method.
        async function scan_qr(url) {
            try {
                const response = await axios.get(url);
                document.querySelector('.blur-overlay').style.display = 'none';

            } catch (error) {
                alert('Invalid QR');
                document.querySelector('.blur-overlay').style.display = 'none';

            }
        }
    </script>
    <style>
        .blur-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(4px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        #video-container {
            position: relative;
            width: 100%; /* adjust as needed */
            height: 100%; /* adjust as needed */
        }

        #video-container video {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .qr-scanner {
            width: 200px;
            height: 200px;
            border: 2px solid #ff5c00;
            position: relative;
            overflow: hidden;
        }

        .scanner-line {
            width: 100%;
            height: 10px;
            background-color: #ff5c00;
            animation: scan 2s infinite linear;
        }

        @keyframes scan {
            0% {
                transform: translateY(0);
            }
            100% {
                transform: translateY(180px);
            }
        }
    </style>
</x-filament-panels::page>
