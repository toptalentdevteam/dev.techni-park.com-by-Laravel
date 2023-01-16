
    @foreach($documents as $key => $value)
        @if(!is_numeric($key))
            <li><a href="#">{{$key}}</a>
                <ul>
                    @include('boilerplate::recursive', ['documents' => $value]) 
                </ul>
            </li>
        @else
            <li class="{{ $value['extension'] ?? '' }}"><table><tbody><tr><td class="col-md-5 no-padding"><a href="{{ $value['dirname'] ?? '' }}" target="_blank">{{ $value["name"] ?? '' }}</a></td><td class="col-md-3">{{ $value["size"] ?? '' }}</td><td class="col-md-6">{{ $value["date"] ?? '' }}</td></tr></tbody></table></li>
        @endif
    @endforeach 
