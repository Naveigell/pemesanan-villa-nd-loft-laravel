<div class="alert alert-success alert-dismissible show fade">
    <div class="alert-body">
        @if(@$dismissable)
            <button class="close" data-dismiss="alert">
                <span>×</span>
            </button>
        @endif
        {{ $message ?? '' }}
    </div>
</div>
