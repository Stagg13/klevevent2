@extends('partials.public-admin')
@section('content')

    <!-- Fancybox CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css"/>
    <!-- Lazysizes -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.0/lazysizes.min.js" async></script>

    <link rel="stylesheet" href="{{ asset('gallery_style.css') }}"/>
    <?php
    $img_src = !empty($eventSetting->event_pic) ? asset('assets/eventSettings/' . $eventSetting->event_pic) : asset('assets/eventSettings/1712079294.png');
    ?>

    <div class="hero" style="background: #000 url({{ $img_src }}) center center no-repeat; height: 200px;">
        <div class="container">
            <div class="hero-content">
                <div class="row">
                    <div class="col-md-12">
                        <h1>{{ $eventSetting->first_line_text !== 'null' ? $eventSetting->first_line_text : '' }}</h1>
                    </div>
                    <div class="col-md-6">
                        <p>{{ $eventSetting->second_line_text !== 'null' ? $eventSetting->second_line_text : '' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main class="main" >
        <div class="container">
            <div class="gallery" style="margin-bottom: 50px !important;">
                @foreach($photos as $image)
                    <div class="card">
                        <div class="card-image">
                            <a href="{{  $image->low_res_image }}" data-fancybox="gallery" data-download="{{  $image->image }}">
                                <img data-src="{{ $image->low_res_image }}" class="lazyload" alt="{{ $image->name }}">
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>

    <!-- Fancybox JS -->
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
    <script>
        Fancybox.bind("[data-fancybox='gallery']", {
            Toolbar: {
                display: [
                    "zoom",
                    "download",
                    "close"
                ],
            },
            Html: {
                download: '<a download data-fancybox-download class="fancybox__button fancybox__button--download" title="Download" href="javascript:;">Download</a>',
            },
            on: {
                ready: (fancybox) => {
                    fancybox.$container.addEventListener('click', (event) => {
                        const downloadButton = event.target.closest('[data-fancybox-download]');
                        if (downloadButton) {
                            // Get the current slide element
                            const slideElement = fancybox.getSlide();
                            // Get the download URL from the data-download attribute of the slide element
                            const downloadUrl = slideElement.$trigger.getAttribute('data-download');

                            if (downloadUrl) {
                                // Create a temporary link element
                                const link = document.createElement('a');
                                link.href = downloadUrl;
                                link.download = downloadUrl.split('/').pop(); // Extract the filename from the URL

                                // Trigger the download
                                document.body.appendChild(link); // Append to body for Firefox compatibility
                                link.click();
                                document.body.removeChild(link); // Clean up the DOM
                            } else {
                                console.error('Download URL not found.');
                            }
                        }
                    });
                }
            }
        });
    </script>

    <?php
    $url = url("cloud-storage/shared-events/" . request()->segment(3));
    $shared_ch = true;
    request()->session()->put("shared-events", $url);
    ?>
    @include("partials.event-menu")
    @include('photos.view-photo-options')
    @include('event.view-event-share')
@endsection
