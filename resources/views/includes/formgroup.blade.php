@php
    $xclass = "";
    if (!empty($class)) $xclass .= ' ' . $class;
    if ($errors->has($attribs["name"])) $xclass .= ' is-invalid';
    if (!isset($formobject)) {
        $old = old($attribs["name"]);
    } else {
        $names = explode('-', $attribs["name"]);
        $old = old($attribs["name"], $formobject[$names[count($names)-1]]);
    }
@endphp
<div class="form-group row{{ $errors->has($attribs["name"]) ? ' has-danger' : '' }}">
    <label for="{{ $attribs["name"] }}" class="col-md-4 control-label">
        @if(isset($title))
            <a href="#" class="p-2 float-right" data-toggle="tooltip" data-html="true"
               title="@lang('messages.' . $title)"><i class="far fa-info"></i></a>
        @endif
        {{ $label }}
    </label>

    <div class="col-md-6">
        @if(isset($type))
            @if($type == 'select')
                {{--@include('snippet.formgroup', ['type' => 'select', 'class' => ' roomid',--}}
                {{--'label' => 'Workshop Room', 'attribs' => ['name' => $timeslot->id . '-roomid'],--}}
                {{--'default' => 'Choose a room', 'list' => $rooms, 'option' => 'room_number'])--}}
                {{--@include('snippet.formgroup', ['type' => 'select', 'label' => 'No/Yes?', 'attribs' => ['name' => 'waive'],--}}
                {{--'list' => [['id' => 'No', 'name' => 'No'], ['id' => 'Yes', 'name' => 'Yes']], 'option' => 'name'])--}}
                <select id="{{ $attribs["name"] }}" name="{{ $attribs["name"] }}" class="form-control{{ $xclass }}">
                    @if(!empty($default))
                        <option value="0">{{ $default }}</option>
                    @endif
                    @foreach($list as $item)
                        <option value="{{ $item["id"] }}"{{ $old == $item["id"] ? " selected" : "" }}>{{ $item[$option] }}</option>
                    @endforeach
                </select>
            @elseif($type == 'text')
                {{--@include('snippet.formgroup', ['type' => 'text', 'label' => 'Qualifications',  'attribs' => ['name' => 'qualifications']])--}}
                <textarea id="{{ $attribs["name"] }}" name="{{ $attribs["name"] }}"
                          class="form-control{{ $xclass }}">{{ $old }}</textarea>
            @elseif($type == 'captcha')
                {{--@include('snippet.formgroup', ['type' => 'captcha', 'label' => 'CAPTCHA Test',--}}
                {{--'attribs' => ['name' => 'g-recaptcha-response']])--}}
                {!! NoCaptcha::display() !!}
            @elseif($type == 'submit')
                {{--@include('snippet.formgroup', ['type' => 'submit', 'label' => '', 'attribs' => ['name' => 'Save Changes']])--}}
                <div class="text-lg-right">
                    <input type="submit" class="btn btn-lg btn-primary py-3 px-4" value="{{ $attribs["name"] }}"/>
                </div>
            @elseif($type == 'next')
                {{--@include('snippet.formgroup', ['type' => 'next', 'label' => '', 'attribs' => ['name' => 'Next Camper']])--}}
                <div class="form-group row">
                    <div class="col-md-10 text-md-right">
                        <button type="button" class="btn btn-default nextcamper">{{ $attribs["name"] }}
                            <i class="far fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            @elseif($type == 'info')
                {{--@include('snippet.formgroup', ['type' => 'info', 'label' => 'Email Address', 'attribs' => ['name' => 'email'],--}}
                {{--'default' => $camper->email])--}}
                <span id="{{ $attribs["name"] }}"><strong>{{ $default }}</strong></span>
            @endif
        @else
            {{--@include('snippet.formgroup', ['label' => 'Fee', 'attribs' => ['name' => 'fee']])--}}
            <input id="{{ $attribs["name"] }}" class="form-control{{ $xclass }}" value="{{ $old }}"
            @foreach($attribs as $attrib => $value)
                {{ $attrib }}="{{ $value }}"
            @endforeach
            />
            @if(isset($hidden))
                <input type="hidden" id="{{ $attribs["name"] . $hidden }}" name="{{ $attribs["name"] . $hidden }}"/>
            @endif
        @endif

        @error($attribs["name"])
            <span class="invalid-feedback" style="display: block;">
                <strong>{{ $errors->first($attribs["name"]) }}</strong>
            </span>
        @enderror
    </div>
</div>