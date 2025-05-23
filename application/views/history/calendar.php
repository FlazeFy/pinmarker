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
        background: var(--primaryColor);
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
        background: var(--primaryLightColor) !important;
        padding: 6px !important;
        border-radius: 10px 10px 0 0;
    }

    /* Day Event */
    .fc-event-title{
        color: var(--secondaryColor) !important;
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
        background: var(--primaryLightColor);
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
        color: var(--secondaryColor) !important;
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
    function checkFullcalendarMidnight(datetime) {
        // For fullcalendar end date missmatch day
        datetime = new Date(datetime) 
        const hr = datetime.getHours()
        if(hr == 23){
            return `${datetime.getFullYear()}-${("0" + (datetime.getMonth() + 1)).slice(-2)}-${("0" + datetime.getDate()).slice(-2)} ${("0" + datetime.getHours()).slice(-2)}:59:00`;
        } else {
            return datetime
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar_all_my_visit');
        var offset = getUTCHourOffset();
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: sessionStorage.getItem('locale'),
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'title',
                right: 'prev,next today',
                center: null,
            },
            selectable: true,
            navLinks: true, 
            eventLimit: true,
            dayMaxEvents: 4,
            events: [
                <?php
                    $i = 0;
                    
                    foreach($dt_all_my_visit_header as $dt){
                        $desc = 'Visit';
                        if($dt->visit_desc != null){
                            $desc = $dt->visit_desc;
                        }

                        if($dt->pin_name){
                            $desc = "$desc at $dt->pin_name";
                        } 

                        echo "
                            {
                                groupId: '$dt->id',
                                title: `$desc`,
                                start: getDateToContext('$dt->created_at','calendar_server'),
                                end: checkFullcalendarMidnight(getDateToContext('$dt->created_at','calendar_server')),
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
                window.location.href = "/DetailVisitController/view/" +info.event.extendedProps.id;
            },
        });
        calendar.render();
    });
</script>