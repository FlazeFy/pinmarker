<div class="modal fade" id="myContactModel" tabindex="-1" aria-labelledby="addGalleriesLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">My Person in Touch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id='close-my-person-modal-btn'></button>
            </div>
            <div class="modal-body">
                <?php 
                    foreach($dt_my_contact as $dt){
                        echo "
                            <div class='p-3 mb-2' style='border-radius: 15px; border: 2px solid black;'>
                                <div class='d-flex justify-content-start'>
                                    <input class='form-check-input check-person' type='checkbox'>
                                    <div>
                                        <h6 class='mb-0 person-name'>$dt->pin_person</h6>
                                        <p class='mb-0 text-secondary'>Found in marker : $dt->pin_list</p>
                                    </div>
                                </div>
                            </div>
                        ";
                    }
                ?>
                <input id="visit-add-contact-target" hidden/>
                <a class="btn btn-dark w-100 py-2 px-3" id='add-selected-person-btn'><i class="fa-solid fa-copy"></i> Add Selected Person</a>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(document).on('click', '.see-person-btn', function() {
            const idx = $(this).index('.see-person-btn')
            $('.form-check-input.check-person').prop('checked', false)

            const visit_with = $('.visit-with').eq(idx).val()
                .split(/,\s+and\s+|,\s+/).map(dt => dt.toLowerCase())

            $('.form-check-input.check-person').each(function() {
                let pin_person = $(this).siblings('div').find('.person-name').text().toLowerCase()

                if (visit_with.includes(pin_person)) {
                    $(this).prop('checked', true)
                }
            });
            
            $('#visit-add-contact-target').val(idx)
        })

        $(document).on('blur', '.visit-with', function() {
            const idx = $(this).index('.visit-with')
            const val = $('.visit-with').eq(idx).val().trim()
            if(val.trim() != ''){
                const visit_with = val.split(/,\s+and\s+|,\s+/).map(dt => dt.toLowerCase())
                let cleanVisitWith = ''

                if(visit_with.length > 1){
                    cleanVisitWith = visit_with.slice(0, -1)
                            .map(dt => ucEachWord(dt))
                            .join(', ') + `, and ${ucEachWord(visit_with[visit_with.length - 1])}`
                } else {
                    cleanVisitWith = ucEachWord(visit_with[0])
                }

                $(this).eq(idx).val(cleanVisitWith)
            }
        })

        $(document).on('click', '#add-selected-person-btn', function(){
            const idx = $('#visit-add-contact-target').val()
            let persons = []

            $('.form-check-input.check-person:checked').each(function() {
                let pin_person = $(this).siblings('div').find('.person-name').text()
                persons.push(pin_person)
            });

            let person_list = ''
            if (persons.length > 1) {
                person_list = persons.slice(0, -1)
                    .map(dt => `<b>${dt}</b>`)
                    .join(', ') + `, and <b>${persons[persons.length - 1]}</b>`
            } else if (persons.length == 1) {
                person_list = `<b>${persons[0]}</b>`
            }

            if(persons.length == 0){
                Swal.fire({
                    title: 'Failed!',
                    text: `No person is selected`,
                    icon: 'error',
                });      
            } else {
                Swal.fire({
                    title: 'Success!',
                    html: `Successfully add ${person_list} to form visit with`,
                    icon: 'success',
                }); 
            }
            $('.visit-with').eq(idx).val(stripHtmlTag(person_list))
        })
    })
</script>