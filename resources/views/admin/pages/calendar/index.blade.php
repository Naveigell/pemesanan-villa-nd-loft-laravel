@extends('layouts.admin.admin')

@section('content-title', 'Kalender Booking')

@push('stack-style')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.5/locales-all.global.min.js"></script>
    <style>
        .fc-event {
            text-align: left;
            height: 35px;
            padding-left: 7px;
            cursor: pointer;
        }

        .fc-event-main {
            height: 100%;
            display: flex;
            align-items: center;
        }
    </style>
    <style>
        .field:before {
            content: ': ';
        }
    </style>
@endpush

@section('content-body')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div id="calendar"></div>
                    <input type="hidden" id="api-calendar-endpoint" value="{{ route('api.v1.admin.calendars.index') }}">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('stack-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            var events = [];

            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id',
                datesSet: function (event) {
                    var endpoint = document.getElementById('api-calendar-endpoint').value;

                    $.ajax({
                        url: endpoint,
                        method: 'GET',
                        data: {
                            month: moment(calendar.getDate()).format('MM')
                        }
                    }).done(function (response) {
                        events = response.data;

                        calendar.removeAllEvents();
                        calendar.addEventSource(formatEvents(events));
                    });
                },
                eventClick: function (info) {

                    fillModalField(info.event.extendedProps);

                    $('#modal--customer-calendar').modal('show');
                }
            });
            calendar.render();
        });

        /**
         * Create object format for calendar following the event source format below
         * @link https://fullcalendar.io/docs/event-object
         *
         * @param {Array} bookings
         * @returns {Array<Object>}
         */
        function formatEvents(bookings) {
            return bookings.map(function(booking) {
                return {
                    title: 'Kamar ' + booking.room.name + ' - ' + booking.customer_name,
                    start: moment(booking.from_date).format('YYYY-MM-DD'),
                    // adding 1 day because in fullcalendar it render the end date until before the next day
                    end: moment(booking.until_date).add(1, 'days').format('YYYY-MM-DD'),
                    backgroundColor: booking.room.color,
                    extendedProps: booking,
                };
            });
        }

        /**
         * Add booking data to modal
         *
         * @param booking
         */
        function fillModalField(booking) {
            $('#code').html(booking.code);
            $('#name').html(booking.customer_name);
            $('#email').html(booking.customer_email);
            $('#phone').html(booking.customer_phone);
            $('#address').html(booking.customer_address);
            $('#room').html(booking.room.name);
            $('#days').html(booking.booking_days + ' hari');
            $('#date').html(moment(booking.from_date).format('dddd, D MMMM YYYY') + ' s/d. ' + moment(booking.until_date).format('dddd, D MMMM YYYY'));
        }
    </script>
@endpush

@section('content-modal')
    @include('admin.shared.calendar.customer_modal')
@endsection
