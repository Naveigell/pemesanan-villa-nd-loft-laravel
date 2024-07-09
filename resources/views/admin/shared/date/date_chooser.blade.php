<section class="section pb-4">
    <div class="container">
        <div class="row check-availabilty" id="next">
            <div class="block-32" data-aos="fade-up" data-aos-offset="-200">
                @php
                    $case = \App\Enums\RoomPriceTypeEnum::tryFrom(request('type', \App\Enums\RoomPriceTypeEnum::DAY->value));
                @endphp
                <form action="{{ route('rooms.index') }}" method="get">
                    <div class="row">
                        <div class="col-md-3 mb-4 mb-lg-4 col-lg-3">
                            <label for="checkout_date" class="font-weight-bold text-black">Tipe</label>
                            <select name="type" id="type" class="form-control">
                                @foreach(\App\Enums\RoomPriceTypeEnum::cases() as $type)
                                    <option {{ request('type') === $type->value ? 'selected' : '' }} value="{{ $type->value }}">{{ $type->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3 mb-lg-3 col-lg-3">
                            <label for="checkin_date" class="font-weight-bold text-black">Check In</label>
                            <div class="field-icon-wrap">
                                <div class="icon"><span class="icon-calendar"></span></div>
                                <input type="{{ $case->fieldType() }}" id="from" name="from" class="form-control" value="{{ request('from') }}">
                            </div>
                        </div>
                        <div class="col-md-3 mb-4 mb-lg-4 col-lg-3">
                            <label for="checkout_date" class="font-weight-bold text-black">Check Out</label>
                            <div class="field-icon-wrap">
                                <div class="icon"><span class="icon-calendar"></span></div>
                                <input type="{{ $case->fieldType() }}" id="until" name="until" class="form-control" value="{{ request('until') }}">
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 align-self-center" style="transform: translateY(13%);">
                            <button class="btn btn-primary btn-block text-white" type="submit">Cek Ketersediaan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if($message = session('success'))
        <script>alert('{{ $message }}');</script>
    @endif
</section>
<script>
    const TYPE = Object.freeze({
        DAY: 'day',
        MONTH: 'month',
        YEAR: 'year',
    });

    document.addEventListener('DOMContentLoaded', function () {
        const type = document.getElementById('type');
        const from = document.getElementById('from');
        const until = document.getElementById('until');

        type.addEventListener('change', function (event) {
            const value = event.target.value;

            if (value === TYPE.MONTH) {
                changeElementTypeAttribute('month')
            } else if (value === TYPE.YEAR) {
                changeElementTypeAttribute('number')

                from.min = new Date().getFullYear();
                until.min = new Date().getFullYear();
            } else {
                changeElementTypeAttribute('date')
            }
        });

        /**
         * Change element type
         *
         * @param type
         */
        function changeElementTypeAttribute(type) {
            from.type = type;
            until.type = type;
        }
    });
</script>
