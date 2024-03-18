<style>
    #calendar_all_my_visit {
        width: 100%;
    }
    /* Main */
    #calendar_all_my_visit a {
        text-decoration: none !important;
        color:black;
    }

    .fc-h-event{
        border: none;
        border-radius: 15px;
    }

    /* Toolbar */
    .fc-header-toolbar.fc-toolbar.fc-toolbar-ltr{
        position: relative;
    }
    .fc-toolbar-chunk .fc-button-group button, .fc-today-button.fc-button.fc-button-primary{
        padding: 6px 10px !important;
        border: 1.75px solid black !important;
        background: transparent;
        color: black;
    }
    .fc-toolbar-chunk .fc-button-group button.fc-button-active, .fc-toolbar-chunk .fc-button-group button:hover, .fc-today-button.fc-button.fc-button-primary:disabled{
        background: white !important;
        color: black !important;
        border: none; 
    }
    .fc-header-toolbar.fc-toolbar.fc-toolbar-ltr .fc-toolbar-chunk:nth-child(2n)  {
        position: absolute;
        margin-left: 18vh;
    }
    .fc-next-button.fc-button.fc-button-primary, .fc-prev-button.fc-button.fc-button-primary{
        border: none !important;
    }
    .calendar-tag-holder {
        position: absolute; 
        right: 20vh; 
        z-index: 99;
    }
    .calendar-tag-holder .btn-primary{
        background: white !important;
        color: black !important;
        z-index: 99 !important;
    }

    /* Day Grid */
    .fc-daygrid-event-dot{
        border:none !important;
    }
    .fc .fc-daygrid-day.fc-day-today{
        background: transparent;
    }
    .fc .fc-daygrid-day.fc-day-today a.fc-daygrid-day-number{
        background: black;
        border-radius: 15px;
        width: 25px;
        height: 25px;
        margin: 10px;
        color: white !important;
        padding-top: 4px !important; 
        padding-inline: 6px !important; 
    }
    a.fc-daygrid-day-number{
        font-size: 12px; 
    }
    .fc-event-time{
        display:none;
    }
    .fc-popover-header  {
        background: black !important;
        padding: 6px !important;
        border-radius: 10px 10px 0 0;
    }

    /* Day Event */
    .fc-event-title{
        color: black !important;
        white-space: normal !important;
        font-weight: 500;
    }
    .fc-daygrid-event, .fc-timegrid-event{
        -webkit-transition: all 0.4s;
        -o-transition: all 0.4s;
        transition: all 0.4s;
        z-index: 999 !important;
    }
    .fc-daygrid-event:hover, .fc-timegrid-event:hover{
        transform: scale(1.075);
        box-shadow: rgba(0, 0, 0, 0.3) 0px 2.6px 6px;
    }
    th.fc-col-header-cell.fc-day{
        background: black;
        padding: var(--spaceSM);
        border: 1.25px solid black;
    }
    table.fc-scrollgrid.fc-scrollgrid-liquid{
        border: 1.25px solid black!important;
        border-radius: 15px; /*Fix this*/
    }
    th.fc-col-header-cell.fc-day a{
        font-size: 14px;
        margin: 6px;
        color: white !important;
    }
    td.fc-daygrid-day.fc-day{
        border: 1.25px solid black!important;
        height: 40px !important;
    }
    .fc-daygrid-event, .fc-timegrid-event{
        background: white !important;
        white-space: normal !important;
        margin: 0 10px 10px 15px !important;
        font-weight: 500;
        border-radius: 0 10px 10px 0;
        border-left: 2.5px solid black !important;
        position: relative;
        cursor: pointer;
        border-right: none !important;
        border-top: none !important;
        border-bottom: none !important;
        box-shadow: rgba(0, 0, 0, 0.14) 0px 2.4px 4px;
    }
    .fc-daygrid-event:hover {
        border: 1.5px solid black;
    }

    .fc-daygrid-event.fc-daygrid-dot-event{
        padding: 6px 0 10px 4px !important;
    }
    .fc-daygrid-event.fc-daygrid-block-event{
        padding: 6px 0 10px 10px !important;
    }
    .fc-v-event .fc-event-title-container{
        padding: 10px !important;
    }

    .fc-daygrid-event:last-child, .fc-timegrid-event:last-child {
        padding-bottom: 0;
    }
    .fc-daygrid-event::before, .fc-timegrid-event::before {
        content: "";
        position: absolute;
        width: 12.5px;
        height: 12.5px;
        border-radius: 50px;
        left: -7.5px;
        top: 30%;
        background: white;
        border: 2.5px solid black;
    }
    .fc-daygrid-event .fc-event-title, .fc-timegrid-event .fc-event-title{
        font-size: 10px;
        font-weight: 500;
    }

    /* Show More */
    .fc-popover.fc-more-popover{
        border-radius: 10px;
        -webkit-transition: all 0.25s;
        -o-transition: all 0.25s;
        transition: all 0.25s;
    }
    .fc-popover.fc-more-popover .fc-popover-title{
        font-weight: 500;
        color: white;
    }
    .fc-popover.fc-more-popover .fc-popover-body{
        flex-direction: column;
        height: 60vh;
        z-index: 999;
        overflow-y: scroll;
    }
    .fc-popover-close {
        -webkit-transition: all 0.4s !important;
        -o-transition: all 0.4s !important;
        transition: all 0.4s !important;
    }
    .fc-popover-close:hover{
        transform: scale(1.1);
    }
    .fc-popover-close.fc-icon.fc-icon-x {
        width: 32px !important;
        height: 32px !important;
        padding: 6px !important;
        box-shadow: none;
        background: white;
        opacity: 1;
        border-radius: 10px;
        color: black;
    }
    .fc-daygrid-more-link.fc-more-link{
        color: black !important; 
        float: right !important;
        top: -5px;
        margin-right: 8px;
        font-size: 12px;
        font-weight: 400;
    }

    /* Mobile style */
    @media (max-width: 767px) {
        #calendar_all_my_visit {
            width: 1100px !important;
        }
        #calendar_all_my_visit a{
            font-size: 12px;
        }
        .calendar-holder {
            display: flex; 
            flex-direction: column; 
            max-width: 100vh; 
            overflow-x: scroll;
        }
        .fc-toolbar-title {
            font-size: 18px !important;
            position: absolute;
            left: 30px !important;
            top: -10px;
            white-space: nowrap;
        }
        th.fc-col-header-cell.fc-day {
            padding: 8px;
        }
    }

</style>

<div class="calendar-holder">
    <div id="calendar_all_my_visit"></div>
</div>

<script type="text/javascript">
    function getDateToContext(datetime, type){
        if(datetime){
            const result = new Date(datetime);

            if (type == "full") {
                const now = new Date(Date.now());
                const yesterday = new Date();
                const tomorrow = new Date();
                yesterday.setDate(yesterday.getDate() - 1);
                tomorrow.setDate(tomorrow.getDate() + 1);
                
                if (result.toDateString() === now.toDateString()) {
                    return ` ${messages('today_at')} ${("0" + result.getHours()).slice(-2)}:${("0" + result.getMinutes()).slice(-2)}`;
                } else if (result.toDateString() === yesterday.toDateString()) {
                    return ` ${messages('yesterday_at')} ${("0" + result.getHours()).slice(-2)}:${("0" + result.getMinutes()).slice(-2)}`;
                } else if (result.toDateString() === tomorrow.toDateString()) {
                    return ` ${messages('tommorow_at')} ${("0" + result.getHours()).slice(-2)}:${("0" + result.getMinutes()).slice(-2)}`;
                } else {
                    return ` ${result.getFullYear()}/${(result.getMonth() + 1)}/${("0" + result.getDate()).slice(-2)} ${("0" + result.getHours()).slice(-2)}:${("0" + result.getMinutes()).slice(-2)}`;
                }
            } else if (type == "24h" || type == "12h") {
                return `${("0" + result.getHours()).slice(-2)}:${("0" + result.getMinutes()).slice(-2)}`;
            } else if (type == "datetime") {
                return ` ${result.getFullYear()}/${(result.getMonth() + 1)}/${("0" + result.getDate()).slice(-2)} ${("0" + result.getHours()).slice(-2)}:${("0" + result.getMinutes()).slice(-2)}`;
            } else if (type == "date") {
                return `${result.getFullYear()}-${("0" + (result.getMonth() + 1)).slice(-2)}-${("0" + result.getDate()).slice(-2)}`;
            } else if (type == "calendar") {
                const result = new Date(datetime);
                const offsetHours = getUTCHourOffset();
                result.setUTCHours(result.getUTCHours() + offsetHours);
            
                return `${result.getFullYear()}-${("0" + (result.getMonth() + 1)).slice(-2)}-${("0" + result.getDate()).slice(-2)} ${("0" + result.getHours()).slice(-2)}:${("0" + result.getMinutes()).slice(-2)}:00`;
            }        
        } else {
            return "-";
        }
    }

    function getUTCHourOffset() {
        const offsetMi = new Date().getTimezoneOffset();
        const offsetHr = -offsetMi / 60;
        return offsetHr;
    }


    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar_all_my_visit');
        var offset = getUTCHourOffset();
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: sessionStorage.getItem('locale'),
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                //right: 'dayGridMonth'
                right: 'dayGridMonth',
            },
            selectable: true,
            navLinks: true, 
            eventLimit: true,
            dayMaxEvents: 4,
            events: [
                <?php
                    $i = 0;
                    
                    foreach($dt_all_my_visit_header as $dt){
                        echo "
                            {
                                groupId: '$dt->id',
                                title: `$dt->visit_desc at $dt->pin_name `,
                                start: getDateToContext('$dt->created_at','calendar'),
                                end: getDateToContext('$dt->created_at','calendar'),
                                extendedProps: {
                                    id: '$dt->id'
                                }
                            },
                        ";
                        $i++;
                    }
                ?>
            ],
            eventClick:  function(info, jsEvent, view) {
                window.location.href = "http://127.0.0.1:8000/visit/detail/" +info.event.extendedProps.id;
            },
        });
        calendar.render();
    });
</script>