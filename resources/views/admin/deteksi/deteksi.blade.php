@extends('admin.layouts.app')

@section('title', 'Deteksi Uang Rupiah')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Deteksi Uang Rupiah</h6>
            </div>
            <div class="card-body text-center">
                <div class="mb-4">
                    <div class="form-group">
                        <label class="font-weight-bold mb-2 d-block">Pilih Kamera</label>
                        <select id="cameraSelect" class="form-control" style="max-width: 300px; margin: 0 auto;">
                            <option value="">-- Memuat kamera... --</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="font-weight-bold mb-3 d-block">Kamera Deteksi</label>
                    <video id="video" width="100%" class="border rounded" style="max-width: 100%; max-height: 400px; background-color: #000; display: block; margin: 0 auto;" autoplay></video>
                </div>

                <div class="alert alert-info" id="result" style="display:none;">
                    <h5 id="result-text" class="mb-0"></h5>
                </div>

                <canvas id="canvas" style="display:none;"></canvas>
            </div>
            <button
                id="detectBtn"
                class="btn btn-primary">
                Deteksi Uang
            </button>

            <div id="countdown" class="mt-2"></div>
        </div>
    </div>
</div>

<script>
let currentStream = null;
let isDetecting = false;

const video = document.getElementById('video');
const canvas = document.getElementById('canvas');

//
// =========================
// LOAD CAMERA LIST
// =========================
//

async function loadCameras() {

    try {

        // izin kamera dulu
        await navigator.mediaDevices.getUserMedia({
            video: true
        });

        const devices =
            await navigator.mediaDevices.enumerateDevices();

        const videoDevices =
            devices.filter(
                device => device.kind === 'videoinput'
            );

        const cameraSelect =
            document.getElementById('cameraSelect');

        cameraSelect.innerHTML = '';

        if (videoDevices.length === 0) {

            cameraSelect.innerHTML =
                '<option>Tidak ada kamera</option>';

            return;
        }

        videoDevices.forEach((device, index) => {

            const option =
                document.createElement('option');

            option.value = device.deviceId;

            option.text =
                device.label ||
                `Kamera ${index + 1}`;

            cameraSelect.appendChild(option);
        });

        // kamera pertama otomatis aktif
        await startCamera(videoDevices[0].deviceId);

    } catch (error) {

        console.error(error);

        alert(
            'Tidak dapat mengakses kamera'
        );
    }
}

//
// =========================
// START CAMERA
// =========================
//

async function startCamera(deviceId) {

    try {

        // stop stream sebelumnya
        if (currentStream) {

            currentStream.getTracks().forEach(
                track => track.stop()
            );
        }

        const constraints = {

            video: {
                deviceId: {
                    exact: deviceId
                },
                width: 1280,
                height: 720
            }
        };

        const stream =
            await navigator.mediaDevices.getUserMedia(
                constraints
            );

        currentStream = stream;

        video.srcObject = stream;

        // tunggu video ready
        video.onloadedmetadata = () => {

            video.play();
        };

    } catch (error) {

        console.error(error);

        alert(
            'Gagal membuka kamera: ' +
            error.message
        );
    }
}

//
// =========================
// BUTTON DETECT + TIMER
// =========================
//

document
    .getElementById('detectBtn')

    .addEventListener(
        'click',
        async function () {

            if (isDetecting) {
                return;
            }

            const countdownDiv =
                document.getElementById(
                    'countdown'
                );

            let timeLeft = 3;

            countdownDiv.innerHTML =
                'Deteksi dalam ' +
                timeLeft +
                ' detik...';

            const interval = setInterval(
                async () => {

                    timeLeft--;

                    if (timeLeft > 0) {

                        countdownDiv.innerHTML =
                            'Deteksi dalam ' +
                            timeLeft +
                            ' detik...';

                    } else {

                        clearInterval(interval);

                        countdownDiv.innerHTML =
                            'Memproses...';

                        await captureAndPredict();

                        countdownDiv.innerHTML =
                            '';
                    }
                },
                1000
            );
        }
    );

//
// =========================
// CAPTURE + PREDICT
// =========================
//

async function captureAndPredict() {

    if (!video.videoWidth) {
        return;
    }

    isDetecting = true;

    try {

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        const ctx = canvas.getContext('2d');

        ctx.drawImage(
            video,
            0,
            0,
            canvas.width,
            canvas.height
        );

        const blob = await new Promise(resolve =>
            canvas.toBlob(
                resolve,
                'image/jpeg',
                0.9
            )
        );

        const formData = new FormData();

        formData.append(
            'image',
            blob,
            'capture.jpg'
        );

        const response = await fetch(
            '{{ route("admin.detect.predict") }}',
            {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN':
                        document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute('content') || ''
                }
            }
        );

        const result = await response.json();

        showResult(result);

    } catch (error) {

        console.error(error);

    } finally {

        isDetecting = false;
    }
}

//
// =========================
// SHOW RESULT
// =========================
//

function showResult(result) {

    const resultDiv =
        document.getElementById('result');

    const resultText =
        document.getElementById('result-text');

    let confidence =
        (result.confidence * 100).toFixed(2);

    let alertClass = 'alert-info';

    if (
        result.prediction.includes('palsu')
    ) {

        alertClass = 'alert-danger';

    } else {

        alertClass = 'alert-success';
    }

    resultDiv.className =
        'alert ' + alertClass;

    resultText.innerHTML =

        '<strong>' +
        result.prediction +
        '</strong>' +

        '<br>' +

        'Confidence: ' +
        confidence +
        '%';

    resultDiv.style.display = 'block';
}

//
// =========================
// CAMERA CHANGE
// =========================
//

document
    .getElementById('cameraSelect')

    .addEventListener(
        'change',
        async function () {

            if (this.value) {

                await startCamera(this.value);
            }
        }
    );

//
// =========================
// INIT
// =========================
//

document.addEventListener(
    'DOMContentLoaded',
    loadCameras
);
</script>

@endsection