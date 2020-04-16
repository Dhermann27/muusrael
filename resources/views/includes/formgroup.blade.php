@php
    $xclass = "";
    if (!empty($class)) $xclass .= ' ' . $class;
    if (!isset($formobject)) {
        $old = old($attribs["name"]);
    } else {
        $names = explode('-', $attribs["name"]);
        $old = old($attribs["name"], $formobject[$names[count($names)-1]]);
    }
@endphp
<div class="form-group row @error($attribs["name"]) has-danger @enderror">
    <label for="{{ $attribs["name"] }}" class="col-md-4 col-form-label text-md-right">
        @if(isset($title))
            <a href="#" class="p-2 float-right" data-toggle="tooltip" data-html="true"
               title="@lang('registration.' . $title)"><i class="far fa-info"></i></a>
        @endif
        {{ $label }}
    </label>

    <div class="col-md-6">
        @if(isset($type))
            @if($type == 'select')
                {{--@include('includes.formgroup', ['type' => 'select', 'class' => ' roomid',--}}
                {{--'label' => 'Workshop Room', 'attribs' => ['name' => $timeslot->id . '-roomid'],--}}
                {{--'default' => 'Choose a room', 'list' => $rooms, 'option' => 'room_number'])--}}
                {{--@include('includes.formgroup', ['type' => 'select', 'label' => 'No/Yes?', 'attribs' => ['name' => 'waive'],--}}
                {{--'list' => [['id' => 'No', 'name' => 'No'], ['id' => 'Yes', 'name' => 'Yes']], 'option' => 'name'])--}}
                <select id="{{ $attribs["name"] }}" name="{{ $attribs["name"] }}"
                        class="form-control @error($attribs["name"]) is-invalid @enderror {{ $xclass }}">
                    @if(!empty($default))
                        <option value="0">{{ $default }}</option>
                    @endif
                    @foreach($list as $item)
                        <option
                            value="{{ $item["id"] }}"{{ $old == $item["id"] ? " selected" : "" }}>{!! $item[$option] !!}</option>
                    @endforeach
                </select>
            @elseif($type == 'text')
                {{--@include('includes.formgroup', ['type' => 'text', 'label' => 'Qualifications',  'attribs' => ['name' => 'qualifications']])--}}
                <textarea id="{{ $attribs["name"] }}" name="{{ $attribs["name"] }}"
                          class="form-control @error($attribs["name"]) is-invalid @enderror {{ $xclass }}">{{ $old }}</textarea>
            @elseif($type == 'captcha')
                {{--@include('includes.formgroup', ['type' => 'captcha', 'label' => 'CAPTCHA Test',--}}
                {{--'attribs' => ['name' => 'g-recaptcha-response']])--}}
                <span id="captchaimg">{!! captcha_img() !!}</span>
                <button type="button" id="refreshcaptcha" class="btn btn-success"><i class="fas fa-sync-alt"></i></button>
                <input id="captcha" name="{{ $attribs["name"] }}"
                       class="form-control mt-2 @error('captcha') is-invalid @enderror" />
            @elseif($type == 'submit')
                {{--@include('includes.formgroup', ['type' => 'submit', 'label' => '', 'attribs' => ['name' => 'Save Changes']])--}}
                <div class="text-lg-right">
                    <button type="submit" class="btn btn-primary py-3 px-4">
                        {!! $attribs["name"] !!}
                    </button>
                </div>
            @elseif($type == 'next')
                {{--@include('includes.formgroup', ['type' => 'next', 'label' => '', 'attribs' => ['name' => 'Next Camper']])--}}
                <div class="form-group row">
                    <div class="col-md-10 text-md-right">
                        <button type="button" class="btn btn-default nextcamper">{{ $attribs["name"] }}
                            <i class="far fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            @elseif($type == 'info')
                {{--@include('includes.formgroup', ['type' => 'info', 'label' => 'Email Address', 'attribs' => ['name' => 'email'],--}}
                {{--'default' => $camper->email])--}}
                <span id="{{ $attribs["name"] }}"><strong>{{ $default }}</strong></span>
            @endif
        @else
            {{--@include('includes.formgroup', ['label' => 'Fee', 'attribs' => ['name' => 'fee']])--}}
            <input id="{{ $attribs["name"] }}"
                   class="form-control @error($attribs["name"]) is-invalid @enderror {{ $xclass }}" value="{{ $old }}"
            @foreach($attribs as $attrib => $value)
                {{ $attrib }}="{{ $value }}"
            @endforeach
            />
            @if(isset($hidden))
                <input type="hidden" id="{{ $attribs["name"] . $hidden }}" name="{{ $attribs["name"] . $hidden }}"/>
            @endif
        @endif


        @error($attribs["name"])
        <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

@section('script')
    <script type="text/javascript">
        $('button#refreshcaptcha').click(function(){
            $.ajax({
                type:'GET',
                url:'/refreshcaptcha',
                success:function(data){
                    $("span#captchaimg").html(data.captcha);
                }
            });
        });
    </script>
    @endsection
