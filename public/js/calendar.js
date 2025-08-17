// Define the calendar variable in the global scope
var _token = $('meta[name="csrf-token"]').attr('content');

var selectedResource;
var selectedYear;
var selectedMonth;

import { Calendar } from '@fullcalendar/core';
import interactionPlugin from '@fullcalendar/interaction';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';


$(document).ready(function () {

    NewFullCalendarInstance();

});


function NewFullCalendarInstance(){

    if ($("#calendar_test").length) {
        if ($("#calendar-events").length) {
            new _fullcalendar_interaction__WEBPACK_IMPORTED_MODULE_1__.Draggable($("#calendar-events")[0], {
                itemSelector: ".event",
                eventData: function eventData(eventEl) {
                    return {
                        title: $(eventEl).find(".event__title").html(),
                        duration: {
                            days: parseInt($(eventEl).find(".event__days").text())
                        }
                    };
                }
            });
        }

        var calendar = new _fullcalendar_core__WEBPACK_IMPORTED_MODULE_0__.Calendar($("#calendar_test")[0], {
            plugins: [_fullcalendar_interaction__WEBPACK_IMPORTED_MODULE_1__["default"], _fullcalendar_daygrid__WEBPACK_IMPORTED_MODULE_2__["default"], _fullcalendar_timegrid__WEBPACK_IMPORTED_MODULE_3__["default"], _fullcalendar_list__WEBPACK_IMPORTED_MODULE_4__["default"]],
            droppable: true,
            headerToolbar: {
                left: "prev,next today",
                center: "title",
                right: "dayGridMonth,timeGridWeek,timeGridDay,listWeek"
            },
            initialDate: "2021-01-12",
            navLinks: true,
            editable: true,
            dayMaxEvents: true,
            events: [{
                title: "SOMBAGAY",
                start: "2021-01-05",
                end: "2021-01-08"
            }, {
                title: "PINOSILAY",
                start: "2021-01-11",
                end: "2021-01-15"
            }, {
                title: "LOMBAG KAON",
                start: "2021-01-17",
                end: "2021-01-21"
            }, {
                title: "PA BAHO-AY UG OTOT",
                start: "2021-01-21",
                end: "2021-01-24"
            }, {
                title: "WALA LANG",
                start: "2021-01-24",
                end: "2021-01-27"
            }],
            drop: function drop(info) {
                if ($("#checkbox-events").length && $("#checkbox-events")[0].checked) {
                    $(info.draggedEl).parent().remove();

                    if ($("#calendar-events").children().length == 1) {
                        $("#calendar-no-events").removeClass("hidden");
                    }
                }
            }
        });
        calendar.render();
    }

}

