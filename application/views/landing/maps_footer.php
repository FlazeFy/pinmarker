<div class="map-footer">
    <div class="map-footer-group">
        <div class="map-footer-box">
            <span class="footer-box-label">Network</span>
            <span class="footer-box-value network-value text-dark">No Internet</span>
        </div>
        <div class="map-footer-box">
            <span class="footer-box-label">Speed</span>
            <span class="footer-box-value speed-value text-dark">0 km/h</span>
        </div>
        <div class="map-footer-box">
            <span class="footer-box-label">Time</span>
            <span class="footer-box-value" id="footer-clock">--:-- --</span>
        </div>
        <button class="btn btn-primary px-4 py-2 fw-bold" id="btn-explorer-mode" style="border-radius:var(--roundedJumbo); font-size:var(--textSM);">
            <i class="fa-solid fa-location-crosshairs me-2"></i>Start Explorer Mode
        </button>
        <button class="map-footer-icon-btn" id="btn-focus-me" title="Focus on Me">
            <i class="fa-solid fa-location-crosshairs"></i>
        </button>
        <button class="map-footer-icon-btn" id="btn-toggle-track" title="Toggle Track">
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
    let explorerInterval = null
    let timerInterval = null
    let explorerSeconds = 0
    let prevLat = null
    let prevLng = null

    $(document).ready(function () {
        const updateClock = () => {
            const now = new Date()
            let hours = now.getHours()
            const minutes = String(now.getMinutes()).padStart(2, '0')
            const ampm = hours >= 12 ? 'PM' : 'AM'
            hours = hours % 12 || 12

            $('#footer-clock').text(`${hours}:${minutes} ${ampm}`)
        }

        const updateNetworkSpeed = () => {
            const connection = navigator.connection || navigator.mozConnection || navigator.webkitConnection

            if (!connection) {
                $('.network-value').text('N/A')
                return
            }

            const downlink = connection.downlink
            let status = 'Fast'
            let colorClass = 'text-success'

            if (downlink < 1) {
                status = 'Slow'
                colorClass = 'text-danger'
            } else if (downlink < 5) {
                status = 'Normal'
                colorClass = 'text-warning'
            }

            $('.network-value').text(status).removeClass('text-dark text-success text-warning text-danger').addClass(colorClass)
        }

        const updateFooterInfo = () => {
            updateClock()
            updateNetworkSpeed()
        }

        updateFooterInfo()
        setInterval(updateFooterInfo, 1000)

        const connection = navigator.connection || navigator.mozConnection || navigator.webkitConnection
        if (connection) connection.addEventListener('change', updateNetworkSpeed)

        const startExplorerMode = () => {
            explorerSeconds = 0
            $('#btn-explorer-mode').removeClass('btn-primary').addClass('btn-danger')

            // Timer tick
            timerInterval = setInterval(() => {
                explorerSeconds++
                const mm = String(Math.floor(explorerSeconds / 60)).padStart(2, '0')
                const ss = String(explorerSeconds % 60).padStart(2, '0')
                $('#btn-explorer-mode').html(
                    `<i class="fa-solid fa-location-crosshairs me-2"></i>Stop Explorer Mode (${mm}:${ss})`
                )
            }, 1000)

            // Location + speed fetch 
            explorerInterval = setInterval(() => {
                navigator.geolocation.getCurrentPosition(position => {
                    const lat = position.coords.latitude
                    const lng = position.coords.longitude

                    // Calculate speed 
                    if (prevLat !== null && prevLng !== null) {
                        const distanceM = calculateDistance(`${prevLat},${prevLng}`,`${lat},${lng}`)
                        const speedKmh = (distanceM / 1000) / (5 / 3600)

                        let speedClass = 'text-dark'
                        if (speedKmh >= 81) speedClass = 'text-danger'
                        else if (speedKmh >= 31) speedClass = 'text-warning'
                        else if (speedKmh >= 6) speedClass = 'text-success'

                        $('.footer-box-value.speed-value').text(`${Math.round(speedKmh)} km/h`).removeClass('text-dark text-success text-warning text-danger').addClass(speedClass)
                    }

                    prevLat = lat
                    prevLng = lng
                    localStorage.setItem('trackLat', lat)
                    localStorage.setItem('trackLng', lng)
                })
            }, 5000)
        }

        const stopExplorerMode = () => {
            clearInterval(explorerInterval)
            clearInterval(timerInterval)
            explorerInterval = null
            timerInterval = null
            explorerSeconds = 0
            prevLat = null
            prevLng = null

            $('#btn-explorer-mode').removeClass('btn-danger').addClass('btn-primary').html(`<i class="fa-solid fa-location-crosshairs me-2"></i>Start Explorer Mode`)
            $('.footer-box-value.speed-value').text('0 km/h').removeClass('text-success text-warning text-danger').addClass('text-dark')
        }

        const askPermissionExplorer = () => {
            // Stop if already active
            if (explorerInterval !== null) {
                stopExplorerMode()
                return
            }

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
                    text: 'Your location will be updated automatically every 5 seconds. This may reduce battery life and consume extra data.',
                    showCancelButton: true,
                    confirmButtonText: 'Enable',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#635bff'
                }).then(result => {
                    if (!result.isConfirmed) {
                        removeUrlParam('explorer')
                        return
                    }

                    navigator.geolocation.getCurrentPosition(
                        position => {
                            prevLat = position.coords.latitude
                            prevLng = position.coords.longitude
                            localStorage.setItem('trackLat', prevLat)
                            localStorage.setItem('trackLng', prevLng)

                            startExplorerMode()

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
        }

        $('#btn-explorer-mode').on('click', function () {
            askPermissionExplorer()
        })

        if (isExplorer) {
            askPermissionExplorer()
        }
    })
</script>