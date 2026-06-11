<div class="map-footer">
    <div class="map-footer-group">
        <div class="map-footer-box">
            <span class="footer-box-label">Speed</span>
            <span class="footer-box-value">10 km/h</span>
        </div>
        <div class="map-footer-box">
            <span class="footer-box-label">Time</span>
            <span class="footer-box-value" id="footer-clock">--:-- --</span>
        </div>
        <button class="btn btn-primary px-4 py-2 fw-bold" id="btn-explorer-mode" style="border-radius:var(--roundedJumbo); font-size:var(--textSM);">
            <i class="fa-solid fa-location-crosshairs me-2"></i>Start Explorer Mode
        </button>
        <button class="map-footer-icon-btn" id="btn-focus-me" title="Toggle Fullscreen">
            <i class="fa-solid fa-location-crosshairs"></i>
        </button>
        <button class="map-footer-icon-btn" id="btn-toggle-track" title="Toggle Fullscreen">
            <i class="fa-solid fa-shoe-prints"></i>
        </button>
        <button class="map-footer-icon-btn" id="btn-fullscreen" title="Toggle Fullscreen">
            <i class="fa-solid fa-expand" id="fullscreen-icon"></i>
        </button>
    </div>
</div>

<style>
    .map-footer {
        position: absolute;
        bottom: var(--spaceMD);
        left: 0;
        right: 0;
        z-index: 1000;
        display: flex;
        justify-content: center;
        pointer-events: none;
    }

    .map-footer-group {
        display: flex;
        align-items: center;
        gap: var(--spaceSM);
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(12px);
        border-radius: var(--roundedJumbo);
        padding: 8px 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        border: 1px solid rgba(199, 196, 216, 0.3);
        pointer-events: all;
    }

    .map-footer-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 4px 14px;
        border-radius: var(--roundedMD);
        background: #f2f3f7;
        min-width: 80px;
    }

    .footer-box-label {
        font-size: 10px;
        font-weight: 600;
        color: #9997b0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .footer-box-value {
        font-size: var(--textSM);
        font-weight: 700;
        color: #464555;
    }

    .map-footer-icon-btn {
        width: 38px;
        height: 38px;
        border: none;
        border-radius: var(--roundedMD);
        background: #f2f3f7;
        color: #464555;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: var(--textMD);
        transition: background 0.2s;
    }

    .map-footer-icon-btn:hover {
        background: #e4e3f0;
    }

    @media (max-width: 768px) {
        .map-footer-group {
            gap: 6px;
            padding: 6px 10px;
        }

        .map-footer-box {
            min-width: 64px;
            padding: 4px 10px;
        }
    }
</style>

<script>
    $(document).ready(function () {
        const updateClock = () => {
            const now = new Date()
            let hours = now.getHours()
            const minutes = String(now.getMinutes()).padStart(2, '0')
            const ampm = hours >= 12 ? 'PM' : 'AM'
            hours = hours % 12 || 12
            $('#footer-clock').text(`${hours}:${minutes} ${ampm}`)
        }
        updateClock()
        setInterval(updateClock, 1000)

        $('#btn-explorer-mode').on('click', function () {
            navigator.permissions.query({ name: 'geolocation' }).then(permission => {
                if (permission.state === 'denied') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Location Disabled',
                        text: 'Please enable location access in your browser settings to use Explorer Mode.',
                        confirmButtonColor: '#635bff'
                    })
                    return
                }

                Swal.fire({
                    icon: 'question',
                    title: 'Enable Explorer Mode?',
                    text: 'Your location will be updated automatically every 3–7 seconds. This may reduce battery life and consume extra data.',
                    showCancelButton: true,
                    confirmButtonText: 'Enable',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#635bff'
                }).then(result => {
                    if (!result.isConfirmed) return

                    navigator.geolocation.getCurrentPosition(
                        () => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Explorer Mode Active',
                                text: 'Your location is now being tracked.',
                                confirmButtonColor: '#635bff',
                                timer: 2000,
                                showConfirmButton: false
                            })
                        },
                        () => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Permission Denied',
                                text: 'Unable to access your location.',
                                confirmButtonColor: '#635bff'
                            })
                        }
                    )
                })
            })
        })

        $('#btn-fullscreen').on('click', function () {
            const mapEl = document.getElementById('map')

            
        })

        document.addEventListener('fullscreenchange', function () {
            if (!document.fullscreenElement) {
                $('#fullscreen-icon').removeClass('fa-compress').addClass('fa-expand')
            }
        })

    })
</script>