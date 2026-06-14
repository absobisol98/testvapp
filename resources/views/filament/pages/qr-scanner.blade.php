<x-filament-panels::page>
    <div id="reader" class="w-full max-w-lg mx-auto rounded-xl overflow-hidden"></div>

    <div class="flex gap-3 justify-center mt-4">
        <button id="btn_start" onclick="start_scan()">
            <div class="bg-primary-500 flex items-center justify-center px-6 py-2.5 rounded-lg hover:bg-primary-400 gap-2">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
                <p class="font-medium text-lg text-white">Start Scanning</p>
            </div>
        </button>
        <button id="btn_stop" onclick="stop_scan()" hidden>
            <div class="bg-danger-500 flex items-center justify-center px-6 py-2.5 rounded-lg hover:bg-danger-400 gap-2">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                </svg>
                <p class="font-medium text-lg text-white">Stop Scanning</p>
            </div>
        </button>
    </div>

    <div class="blur-overlay">
        <div role="status">
            <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-primary-600"
                 viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
            </svg>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script src="https://unpkg.com/axios@1.6.7/dist/axios.min.js"></script>
    <script>
        let html5QrCode = null;
        let scanning = false;
        let lastScanned = null;
        const REFRACTORY_MS = 5000;

        function start_scan() {
            document.getElementById('btn_start').hidden = true;
            document.getElementById('btn_stop').hidden = false;
            document.querySelector('.blur-overlay').style.display = 'flex';

            html5QrCode = new Html5Qrcode("reader");

            const config = {
                fps: 10,
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0,
                supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA],
            };

            Html5Qrcode.getCameras().then(cameras => {
                if (!cameras || cameras.length === 0) {
                    document.querySelector('.blur-overlay').style.display = 'none';
                    alert('No cameras found. Please ensure camera permissions are granted.');
                    stop_scan();
                    return;
                }

                const backCamera = cameras.find(c =>
                    c.label && (c.label.toLowerCase().includes('back') || c.label.toLowerCase().includes('environment'))
                ) || cameras[cameras.length - 1];

                document.querySelector('.blur-overlay').style.display = 'none';
                scanning = true;

                html5QrCode.start(
                    backCamera.id,
                    config,
                    (decodedText) => {
                        const now = Date.now();
                        if (lastScanned && (now - lastScanned) < REFRACTORY_MS) return;
                        lastScanned = now;
                        document.querySelector('.blur-overlay').style.display = 'flex';
                        scan_qr(decodedText);
                    },
                    () => {}
                ).catch(err => {
                    document.querySelector('.blur-overlay').style.display = 'none';
                    console.error('Camera start error:', err);
                    showNotification('Could not start camera: ' + err, 'error');
                    stop_scan();
                });

            }).catch(err => {
                document.querySelector('.blur-overlay').style.display = 'none';
                showNotification('Camera access denied. Please allow camera permissions.', 'error');
                stop_scan();
            });
        }

        function stop_scan() {
            if (html5QrCode && scanning) {
                html5QrCode.stop().catch(() => {});
                html5QrCode.clear();
                scanning = false;
            }
            document.getElementById('btn_start').hidden = false;
            document.getElementById('btn_stop').hidden = true;
        }

        async function scan_qr(url) {
            try {
                const response = await axios.get(url);
                document.querySelector('.blur-overlay').style.display = 'none';
                showNotification(response.data.message || 'Scan successful', 'success');
            } catch (error) {
                document.querySelector('.blur-overlay').style.display = 'none';
                showNotification('Invalid QR code or scan failed.', 'error');
            }
        }

        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg z-[2000] text-white font-medium shadow-lg ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
            notification.style.maxWidth = '90vw';
            notification.textContent = message;
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 4000);
        }
    </script>

    <style>
        .blur-overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-color: rgba(255,255,255,0.6);
            backdrop-filter: blur(4px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        #reader video { border-radius: 0.75rem; }
        #reader { border: none !important; }
        #reader__scan_region { border-radius: 0.75rem; overflow: hidden; }
        #reader__dashboard_section_swaplink { display: none; }
    </style>
</x-filament-panels::page>
