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
    .one, .two {
        color: var(--dangerBG);
    }
    .three, .four {
        color: var(--warningBG);
    }
    .five {
        color: rgb(24, 159, 14);
    }
</style>

<hr>
<div class="d-flex justify-content-between">
    <p class='mt-2 fw-bold'>Person's Review</p>
    <form action="/DetailVisitController/manage_review/<?= $id ?>" method="POST" id="review_form">
        <input hidden name="list_review" id="list_review">
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

        foreach ($person_list as $idx => $dt) {
            echo "
                <div class='col-lg-3 col-md-4 col-sm-6 col-6' id='review-$idx'>
                    <div class='px-2 py-3 mb-3' style='border: 2px solid black; border-radius: 15px;'>
                        <label>$dt</label><br>
                        <div class='d-inline review_holder'>
                            <input hidden name='person' value='$dt'>
                            <span onclick='setStar(1, $idx)' class='star'>★</span>
                            <span onclick='setStar(2, $idx)' class='star'>★</span>
                            <span onclick='setStar(3, $idx)' class='star'>★</span>
                            <span onclick='setStar(4, $idx)' class='star'>★</span>
                            <span onclick='setStar(5, $idx)' class='star'>★</span>
                        </div>
                        <p class='output mb-0'></p>
                    </div>
                </div>";
        }
    ?>
</div>

<script>    
    $( document ).ready(function() {
        $(document).on('click', '#save_review', function () {
            let list_review = [];

            $('.review_holder').each(function () {
                const person = $(this).find("input[name='person']").val()
                const activeStars = $(this).find('.star').filter(function () {
                    return $(this).attr('class') !== 'star'
                }).length

                if (activeStars > 0) {
                    list_review.push(`${person}_${activeStars}`)
                }
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
                rate_text = "Not great, many to improve"
            } else if (n == 3) {
                cls = "three"
                rate_text = "It's okay, but could be better"
            } else if (n == 4) {
                cls = "four"
                rate_text = "Nice! This place has a lot to offer"
            } else if (n == 5) {
                cls = "five"
                rate_text = "Amazing! I'd love to visit again!"
            }
            $(stars[i]).attr("class", `star ${cls}`)
        }

        output.html(rate_text)
    }
</script>
