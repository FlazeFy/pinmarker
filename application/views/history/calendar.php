<style>
    #calendar_all_my_visit {
        width: 100%;
    }
    .fc-h-event{
        border: none;
        border-radius: var(--roundedMD);
    }

    /* Toolbar */
    .fc-toolbar-title {
        font-size: var(--textLG) !important;
    }
    .fc-header-toolbar.fc-toolbar.fc-toolbar-ltr{
        position: relative;
    }
    .fc-toolbar-chunk .fc-button-group button, .fc-today-button.fc-button.fc-button-primary{
        border-radius: var(--roundedLG);
        background: var(--primaryColor) !important;
        font-size: var(--textMD);
        border: none; 
    }
    .fc-toolbar-chunk .fc-button-group button {
        padding-inline: var(--spaceMD);
    }
    .fc-header-toolbar.fc-toolbar.fc-toolbar-ltr .fc-toolbar-chunk:nth-child(2n)  {
        position: absolute;
        margin-left: 18vh;
    }
    .fc-next-button.fc-button.fc-button-primary, .fc-prev-button.fc-button.fc-button-primary{
        border: none !important;
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
        border-radius: var(--roundedMD);
        width: 25px;
        height: 25px;
        margin: var(--spaceSM);
        color: white !important;
        padding-top: 4px !important; 
        padding-inline: 6px !important; 
    }
    a.fc-daygrid-day-number{
        font-size: var(--textSM); 
    }
    .fc-event-time{
        display:none;
    }
    .fc-popover-header  {
        background: var(--primaryColor) !important;
        padding: var(--spaceXXSM) !important;
        border-radius: var(--roundedSM) var(--roundedSM) 0 0;
    }
    .fc .fc-highlight {
        background: transparent !important;
    }

    /* Day Event */
    .fc-event-title{
        color: var(--secondaryColor) !important;
        white-space: normal !important;
        font-weight: 500;
    }
    .fc-daygrid-event, .fc-timegrid-event {
        z-index: 999 !important;
    }
    .fc-daygrid-event:hover, .fc-timegrid-event:hover{
        background: var(--primaryLightColor);
        color: #fff !important;
    }
    th.fc-col-header-cell.fc-day{
        background: var(--primaryColor);
        padding: var(--spaceSM);
        border: 1.25px solid black;
    }
    table.fc-scrollgrid.fc-scrollgrid-liquid{
        border: 1.25px solid black !important;
        border-radius: 15px; /*Fix this*/
    }
    th.fc-col-header-cell.fc-day a{
        font-size: var(--textMD);
        color: #fff !important;
    }
    td.fc-daygrid-day.fc-day{
        border: 1.25px solid black !important;
        height: 40px !important;
    }
    .fc-daygrid-event, .fc-timegrid-event {
        white-space: normal !important;
        margin: 0 var(--spaceSM) var(--spaceSM) 15px !important;
        font-weight: 500;
        border-radius: var(--roundedMD);
        position: relative;
        cursor: pointer;
        box-shadow: rgba(0, 0, 0, 0.14) 0px 2.4px 4px;
    }
    .fc-daygrid-event.fc-daygrid-dot-event{
        padding: var(--spaceMini) !important;
    }
    .fc-v-event .fc-event-title-container{
        padding: 10px !important;
    }
    .fc-daygrid-event:last-child, .fc-timegrid-event:last-child {
        padding-bottom: 0;
    }
    .fc-daygrid-event .fc-event-title, .fc-timegrid-event .fc-event-title{
        font-size: calc(var(--textXSM)*0.9) !important;
        font-weight: 500;
    }

    /* Show More */
    .fc-popover.fc-more-popover{
        border-radius: var(--roundedSM);
    }
    .fc-popover.fc-more-popover .fc-popover-title{
        font-weight: 500;
        color: white;
    }
    .fc-popover.fc-more-popover .fc-popover-body{
        flex-direction: column;
        height: 600px;
        z-index: 999;
        overflow-y: scroll;
    }
    .fc-popover-close:hover{
        transform: scale(1.1);
    }
    .fc-popover-close.fc-icon.fc-icon-x {
        width: 32px !important;
        height: 32px !important;
        padding: var(--spaceXXSM) !important;
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
        margin-right: var(--spaceXSM);
        font-size: var( --textSM);
        font-weight: 400;
    }
    .fc-col-header-cell.fc-day.fc-day-sat {
        border-radius: 0 var(--roundedLG) 0 0;
    }

    /* Mobile style */
    @media (max-width: 767px) {
        #calendar_all_my_visit {
            width: 1100px !important;
        }
        #calendar_all_my_visit a{
            font-size: var(--textSM);
        }
        .calendar-holder {
            display: flex; 
            flex-direction: column; 
            max-width: 100vh; 
            overflow-x: scroll;
        }
        .fc-toolbar-title {
            position: absolute;
            left: 30px !important;
            top: -10px;
            white-space: nowrap;
        }
        th.fc-col-header-cell.fc-day {
            padding: var(--spaceXSM);
        }
    }
</style>

<div class="card mb-2">
    <div class="calendar-holder">
        <div id="calendar_all_my_visit"></div>
    </div>
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
                window.location.href = "/EditVisitController/view/" +info.event.extendedProps.id;
            },
        });
        calendar.render();
    });
</script>