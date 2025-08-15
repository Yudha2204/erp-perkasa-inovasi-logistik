@extends('layouts.app')
@section('content')

<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">
            <div class="pt-5">
                <div class="">
                    <h1 style="font-size: 36px; font-weight: bold; color: #015377;">Setting Alamat & Logo</h1>

                    <form action="{{ route('setting.logo.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="w-full d-flex">
                            <div class="w-full d-flex p-5 gap-5">
                                <svg width="57" height="72" viewBox="0 0 57 72" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <rect width="57" height="72" fill="url(#pattern0_4650_6345)"/>
                                    <defs>
                                    <pattern id="pattern0_4650_6345" patternContentUnits="objectBoundingBox" width="1" height="1">
                                    <use xlink:href="#image0_4650_6345" transform="matrix(0.00502513 0 0 0.00397822 0 -0.00324539)"/>
                                    </pattern>
                                    <image id="image0_4650_6345" width="199" height="253" xlink:href="data:image/png;base64,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"/>
                                    </defs>
                                </svg>
                            </div>
                            <div class="w-full d-flex flex-column p-5 gap-5">
                                <div>
                                    <h1 style="font-size: 14px; color: #37474F; font-weight: 400;">Seting Alamat Invoice</h1>
                                    <div style="width: 600px; height: 48px; border-radius: 10px; border: 1px solid #D4DBEA;" class="d-flex align-items-center p-3 bg-white" >
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M18.5 12.6504V16.3504C18.5 19.4704 15.59 22.0004 12 22.0004C8.41 22.0004 5.5 19.4704 5.5 16.3504V12.6504C5.5 15.7704 8.41 18.0004 12 18.0004C15.59 18.0004 18.5 15.7704 18.5 12.6504Z" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M18.5 7.65C18.5 8.56 18.25 9.4 17.81 10.12C16.74 11.88 14.54 13 12 13C9.46 13 7.26 11.88 6.19 10.12C5.75 9.4 5.5 8.56 5.5 7.65C5.5 6.09 6.22999 4.68 7.39999 3.66C8.57999 2.63 10.2 2 12 2C13.8 2 15.42 2.63 16.6 3.65C17.77 4.68 18.5 6.09 18.5 7.65Z" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M18.5 7.65V12.65C18.5 15.77 15.59 18 12 18C8.41 18 5.5 15.77 5.5 12.65V7.65C5.5 4.53 8.41 2 12 2C13.8 2 15.42 2.63 16.6 3.65C17.77 4.68 18.5 6.09 18.5 7.65Z" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>

                                        @if($photos)
                                            <input type="text" style="width: 100%; border: none; padding: 0 10px; outline: none;" name="address" id="address" value="{{$photos->address}}" required>
                                        @else
                                            <input type="text" style="width: 100%; border: none; padding: 0 10px; outline: none;" name="address" id="address" required>
                                        @endif
                                        
                                    </div>
                                </div>
                                <div>
                                    <h1 style="font-size: 14px; color: #37474F; font-weight: 400;">Uploud Signature</h1>
                                    <div>
                                        @if($photos && $photos->image_path)
                                            <img id="img_preview" src="{{ asset('storage/' . $photos->image_path) }}" alt="img_logo" style="max-width: 200px;">
                                        @else
                                            <img id="img_preview" style="width: 200px; height: 200px;">
                                        @endif
                                        <input type="file" name="image" id="image" style="width: 100%; border: none; padding: 0 10px; outline: none; margin-top: 20px;" name="address" id="address" onchange="previewImage(event)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-full d-flex align-items-center justify-content-end p-5">
                            <button style="width: 161px; height: 54px; border-radius: 10px; border: none; outline: none; font-size: 16px; font-weight: 600; color: #59758B; background-color: #CEDFE9;" type="submit">Upload</button>
                        </div>
                    </form>
                    <!-- <form action="{{ route('setting.logo.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="address">address</label>
                            <textarea name="address" id="address" required></textarea>
                        </div>
                        <div>
                            <label for="image">Image</label>
                            <input type="file" name="image" id="image" required>
                        </div>
                        <div>
                            <button type="submit">Upload</button>
                        </div>
                    </form> -->
                </div>
                <!-- @if($photos)
                    <div style="margin-top: 100px;">
                        <h3>{{ $photos->address }}</h3>
                        <img src="{{ asset('storage/' . $photos->image_path) }}" alt="{{ $photos->title }}" style="max-width: 100%;">
                    </div>
                @endif -->
            </div>

        </div>
        <!-- CONTAINER CLOSED -->
    </div>
</div>
@endsection

@push('scripts')
    <script>
        function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('img_preview');
            output.src = reader.result;
            output.style.display = 'block';
        }
        reader.readAsDataURL(event.target.files[0]);
    }
    </script>
@endpush