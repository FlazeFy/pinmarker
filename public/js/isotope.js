$(document).ready(function() {
    loading() 
    
    var $grid = $('.grid')
    $grid.imagesLoaded(function() {
        checkVideos()
    });

    function checkVideos() {
        let videos = $grid.find('video')
        let loadedVideos = 0
        let totalVideos = videos.length

        if (totalVideos == 0) {
            initIsotope()
        } else {
            videos.each(function() {
                this.onloadeddata = function() {
                    loadedVideos++
                    if (loadedVideos == totalVideos) {
                        initIsotope()
                    }
                }
            })
        }
    }

    function initIsotope() {
        $grid.isotope({
            itemSelector: '.grid-item',
            layoutMode: 'masonry',
        })
        Swal.close()
    }
});