<x-filament-panels::page>
    <div id="video-container" hidden>
        <div id="preview"></div>
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
    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script src="https://unpkg.com/axios@1.6.7/dist/axios.min.js"></script>
    <script type="text/javascript">
        let html5QrCode;
        let isScanning = false;

        function stop_scan() {
            if (html5QrCode && isScanning) {
                html5QrCode.stop().then(() => {
                    document.getElementById('btn_start').style.display = 'block';
                    document.getElementById('btn_stop').style.display = 'none';
                    document.getElementById('video-container').style.display = 'none';
                    isScanning = false;
                }).catch((err) => {
                    console.error('Error stopping scanner:', err);
                });
            }
        }

        async function start_scan() {
            document.querySelector('.blur-overlay').style.display = 'flex';

            try {
                // Initialize HTML5QR scanner
                html5QrCode = new Html5Qrcode("preview");

                // Get available cameras
                const cameras = await Html5Qrcode.getCameras();

                if (cameras && cameras.length > 0) {
                    document.getElementById('btn_stop').style.display = 'block';
                    document.getElementById('btn_start').style.display = 'none';
                    document.getElementById('video-container').style.display = 'block';
                    document.querySelector('.blur-overlay').style.display = 'none';

                    // Camera selection logic for mobile devices (iOS and Android)
                    let selectedCameraId = cameras[0].id; // default to first camera

                    // Check if running on mobile device
                    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
                    const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
                    const isAndroid = /Android/i.test(navigator.userAgent);

                    // For mobile devices, prioritize back camera (environment-facing)
                    if (isMobile) {
                        // First, try to find back camera by label
                        let backCamera = cameras.find(camera => {
                            const label = camera.label.toLowerCase();
                            return (
                                label.includes('back') ||
                                label.includes('environment') ||
                                label.includes('rear') ||
                                label.includes('facing back') ||
                                label.includes('camera2 0') || // Common Android back camera identifier
                                label.includes('camera 0') ||   // Another common identifier
                                label.includes('world facing') ||
                                label.includes('main camera')
                            );
                        });

                        // If no back camera found by label, use position-based logic
                        if (!backCamera) {
                            if (isAndroid) {
                                // For Android, try different positions based on common patterns
                                if (cameras.length >= 2) {
                                    // Check if first camera is NOT front-facing
                                    const firstCameraLabel = cameras[0].label.toLowerCase();
                                    const isFirstCameraFront = firstCameraLabel.includes('front') ||
                                                              firstCameraLabel.includes('user') ||
                                                              firstCameraLabel.includes('facing user');

                                    if (!isFirstCameraFront) {
                                        backCamera = cameras[0]; // Use first camera if it's not explicitly front
                                    } else {
                                        backCamera = cameras[1]; // Use second camera if first is front
                                    }
                                }
                            } else if (isIOS && cameras.length > 1) {
                                // On iOS, back camera is typically the last one
                                backCamera = cameras[cameras.length - 1];
                            }
                        }

                        if (backCamera) {
                            selectedCameraId = backCamera.id;
                        }
                    }

                    console.log('Available cameras:', cameras.map(c => c.label)); // Debug info
                    console.log('Selected camera:', cameras.find(c => c.id === selectedCameraId)?.label); // Debug info

                    // Camera configuration optimized for mobile devices
                    const config = {
                        fps: 10, // Lower FPS for better performance on mobile
                        qrbox: { width: 250, height: 250 }, // QR scanning box
                        aspectRatio: 1.0, // Square aspect ratio
                        disableFlip: false, // Allow flip for user-facing cameras
                        videoConstraints: {
                            facingMode: isMobile ? "environment" : undefined, // Force environment camera on mobile
                            advanced: [{ focusMode: "continuous" }] // Better focus for QR codes
                        }
                    };

                    // Start scanning
                    await html5QrCode.start(
                        selectedCameraId,
                        config,
                        (decodedText, decodedResult) => {
                            console.log('QR Code detected:', decodedText);
                            if (decodedText && isScanning) {
                                document.querySelector('.blur-overlay').style.display = 'flex';
                                scan_qr(decodedText);
                            }
                        },
                        (errorMessage) => {
                            // Handle scan errors silently (they're frequent and normal)
                        }
                    );

                    isScanning = true;

                } else {
                    document.querySelector('.blur-overlay').style.display = 'none';
                    alert('No cameras found.');
                }

            } catch (err) {
                document.querySelector('.blur-overlay').style.display = 'none';
                console.error('Error starting camera:', err);
                alert('Failed to start camera: ' + err.message);
            }
        }

        // Update the scan_qr function to handle notifications better
        async function scan_qr(url) {
            try {
                const response = await axios.get(url);
                document.querySelector('.blur-overlay').style.display = 'none';

                // Show notification in a fixed position
                showNotification(response.data.message || 'Scan successful');

            } catch (error) {
                document.querySelector('.blur-overlay').style.display = 'none';
                showNotification('Invalid QR code', 'error');
            }
        }

        // Add this notification function
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg z-[2000] ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            } text-white`;
            notification.style.maxWidth = '90vw';
            notification.textContent = message;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 3000);
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
            width: 100%;
            height: 400px; /* Set a fixed height for better mobile experience */
        }

        #preview {
            width: 100%;
            height: 100%;
        }

        #preview video {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover; /* Better mobile camera display */
            border-radius: 8px;
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
