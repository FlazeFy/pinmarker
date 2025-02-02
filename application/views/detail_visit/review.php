<style>
    .card {
        max-width: 33rem;
        background: #fff;
        margin: 0 1rem;
        padding: 1rem;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        width: 100%;
        border-radius: 0.5rem;
    }
    .star {
        font-size: var(--textJumbo);
        cursor: pointer;
    }
    .one, .two, .three {
        color: var(--dangerBG);
    }
    .four, .five, .six, .seven {
        color: var(--warningBG);
    }
    .eight, .nine, .ten {
        color: rgb(24, 159, 14);
    }
</style>

<hr>
<div class="d-flex justify-content-between">
    <p class='mt-2 fw-bold'>Person's Review</p>
    <form action="/DetailVisitController/manage_review/<?= $id ?>" method="POST" id="review_form">
        <input hidden name="list_review" id="list_review">
        <div class='d-none' id="list_review_body_holder"></div>
        <a class="btn btn-success px-3" id="save_review"><i class="fa-solid fa-floppy-disk"></i> Save Review</a>
    </form>
</div>
<div class="row">
    <?php 
        function parseNames($inputString) {
            $standardized = str_replace(" and ", ", ", $inputString);
            $namesArray = array_map('trim', explode(",", $standardized));
            $namesArray = array_filter($namesArray, function($name) {
                return !empty($name); 
            });
            
            return $namesArray;
        }
        $visit_with = $dt_detail_visit->visit_with;
        $person_list = parseNames($visit_with);

        $total_review = count($dt_review_history);
        foreach ($person_list as $idx => $dt) {
            $rate = null;
            $reviewed_at = null;
            $star = "";
            $review_body = "";

            if($total_review > 0){
                foreach ($dt_review_history as $rh) {
                    if($rh->review_person == $dt){
                        $rate = $rh->review_rate;
                        $reviewed_at = "<p class='mb-0'>Reviewed at <span class='date-target'>$rh->created_at</span></p>";
                        $numbers = [1 => "one", 2 => "two", 3 => "three", 4 => "four", 5 => "five",6 => "six", 7 => "seven", 8 => "eight", 9 => "nine", 10 => "ten"];
                        $star = $numbers[$rate] ?? "unknown";
                        $review_body = $rh->review_body;
                    }
                }
            }
            $reviewed_box = "<textarea class='form-control review_body' placeholder='Place your review here...' name='review_body[]'>$review_body</textarea>";

            echo "
                <div class='col-lg-4 col-md-6 col-sm-6 col-6' id='review-$idx'>
                    <div class='container bordered review_holder'>
                        <label><a class='text-decoration-none text-dark' href='/DetailPersonController/view/$dt'>$dt</a></label><br>
                        <div class='d-inline'>
                            <input hidden name='person' value='$dt'>
                            <span onclick='setStar(1, $idx)' class='star "; if($rate >= 1){ echo $star; } echo "'>★</span>
                            <span onclick='setStar(2, $idx)' class='star "; if($rate >= 2){ echo $star; } echo "'>★</span>
                            <span onclick='setStar(3, $idx)' class='star "; if($rate >= 3){ echo $star; } echo "'>★</span>
                            <span onclick='setStar(4, $idx)' class='star "; if($rate >= 4){ echo $star; } echo "'>★</span>
                            <span onclick='setStar(5, $idx)' class='star "; if($rate >= 5){ echo $star; } echo "'>★</span>
                            <span onclick='setStar(6, $idx)' class='star "; if($rate >= 6){ echo $star; } echo "'>★</span>
                            <span onclick='setStar(7, $idx)' class='star "; if($rate >= 7){ echo $star; } echo "'>★</span>
                            <span onclick='setStar(8, $idx)' class='star "; if($rate >= 8){ echo $star; } echo "'>★</span>
                            <span onclick='setStar(9, $idx)' class='star "; if($rate >= 9){ echo $star; } echo "'>★</span>
                            <span onclick='setStar(10, $idx)' class='star "; if($rate == 10){ echo $star; } echo "'>★</span>
                        </div>
                        <p class='output mb-0'></p>
                        $reviewed_box
                        $reviewed_at
                    </div>
                </div>";
        }
    ?>
</div>

<script>    
    $( document ).ready(function() {
        $(document).on('click', '#save_review', function () {
            let list_review = []

            $('.review_holder').each(function () {
                const person = $(this).find("input[name='person']").val()
                const activeStars = $(this).find('.star').filter(function () {
                    return $(this).attr('class').trim() !== 'star'
                }).length

                if (activeStars > 0) {
                    list_review.push(`${person}_${activeStars}`)
                }

                const reviewBody = $(this).find('.review_body').clone()
                $('#list_review_body_holder').append(reviewBody)
            });

            const list_review_string = list_review.join(',')
            $('#list_review').val(list_review_string)
            $('#review_form').submit()
        });
    });

    const setStar = (n, id) => {
        const container = $(`#review-${id}`)
        const stars = container.find(".star")
        const output = container.find(".output")
        let rate_text = ""

        stars.each(function () {
            $(this).attr("class", "star")
        })

        for (let i = 0; i < n; i++) {
            if (n == 1) {
                cls = "one"
                rate_text = "Oh no! This place didn't live up to expectations"
            } else if (n == 2) {
                cls = "two"
                rate_text = "Oh no! This place didn't live up to expectations"
            } else if (n == 3) {
                cls = "three"
                rate_text = "Not great, many to improve"
            } else if (n == 4) {
                cls = "four"
                rate_text = "Not great, many to improve"
            } else if (n == 5) {
                cls = "five"
                rate_text = "It's okay, but could be better"
            } else if (n == 6) {
                cls = "six"
                rate_text = "It's okay, but could be better"
            } else if (n == 7) {
                cls = "seven"
                rate_text = "Nice! This place has a lot to offer"
            } else if (n == 8) {
                cls = "eight"
                rate_text = "Nice! This place has a lot to offer"
            } else if (n == 9) {
                cls = "nine"
                rate_text = "Amazing! I'd love to visit again!"
            } else if (n == 10) {
                cls = "ten"
                rate_text = "Amazing! I'd love to visit again!"
            }
            $(stars[i]).attr("class", `star ${cls}`)
        }

        output.html(rate_text)
    }
</script>
