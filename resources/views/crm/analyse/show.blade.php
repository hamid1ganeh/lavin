@extends('crm.master')

@section('content')


<div class="content-page">

    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">

        <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                        <ol class="breadcrumb m-0 IR">
{{--                            {{ Breadcrumbs::render('ask.analysis.show', $ask) }}--}}
                        </ol>
                        </div>
                        <h4 class="page-title">
                            <i class="fas fa-images page-icon"></i>
                             پاسخ آنالیز
                        </h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            @if ($errors->any())
                                <div class="row">
                                    <div class="col-12 alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li class="IR">{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-12">
                                    <p class="IRANYekanRegular"> نام و نام خانوادگی: {{ $ask->user->getFullName() ?? '' }}  </p>
                                    <p class="IRANYekanRegular">  موبایل: {{ $ask->user->mobile ?? '' }}  </p>
                                    <p class="IRANYekanRegular">  عنوان سرویس آنالیز: {{ $ask->analyse->title ?? '' }}  </p>
                                    <p class="IRANYekanRegular">    مبلغ مورد نظر: {{ $ask->price ?? '' }} تومان </p>
                                    <p class="IRANYekanRegular">  زمان درخواست آنالیز: {{ $ask->ask_date_time() ?? '' }} </p>
                                    <p class="IRANYekanRegular">وضعیت:
                                        @if($ask->status == App\Enums\AnaliseStatus::pending)
                                            <span class="badge badge-warning IR p-1">در انتظار</span>
                                        @elseif($ask->status == App\Enums\AnaliseStatus::doctor)
                                            <span class="badge badge-info IR p-1">ارجاع به پزشک</span>
                                        @elseif($ask->status == App\Enums\AnaliseStatus::response)
                                            <span class="badge badge-success IR p-1">پاسخ پزشک</span>
                                        @elseif($ask->status == App\Enums\AnaliseStatus::reject)
                                            <span class="badge badge-danger IR p-1">رد شده</span>
                                        @elseif($ask->status == App\Enums\AnaliseStatus::accept)
                                            <span class="badge badge-primary IR p-1">تایید شده</span>
                                        @endif
                                    </p>
                                    <p class="IRANYekanRegular"> پزشک آنالیز: {{ $ask->doctor->fullname ?? '' }} </p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <label  class="control-label IRANYekanRegular">توضیحات پزشک:</label>
                                     <p class="IRANYekanRegular">{{ $ask->response  }}</p>
                                </div>
                                @if($ask->responseImage != null)
                                    <div class="row">
                                        <div class="col-12">
                                            <label  class="control-label IRANYekanRegular">تصویر آنالیز شده:</label>
                                            <a href="{{  $ask->responseImage->getImagePath() }}" target="_blank" title="نمایش" class="mt-3">
                                                <img src="{{  $ask->responseImage->getImagePath('medium') }}">
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if(!is_null($ask->voice))
                            <div class="row mt-2">
                                <label  class="control-label IRANYekanRegular">فایل صوتی ارسالی:</label>
                                <audio controls id="voice"  style="width: 100%;">
                                    <source src="{{ $ask->voice }}" type="audio/ogg">
                                    <source src="{{ $ask->voice  }}" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            </div>
                            @endif

                                @if($ask->status == App\Enums\AnaliseStatus::response ||
                                    $ask->status == App\Enums\AnaliseStatus::accept ||
                                    $ask->status == App\Enums\AnaliseStatus::reject )
                                    <div class="row mt-5 text-center">
                                        <div class="col-12 col-md-4"></div>
                                        <div class="col-12 col-md-4">
                                            <div class="btn-group mb-3" >
                                                <a  class="btn btn-sm btn-warning" href="#response" data-toggle="modal" title="تایید یا رد؟">
                                                    <i class="fas fa-thumbs-up  plusiconfont"></i>
                                                    <b class="IRANYekanRegular">تایید یا رد سرویس آنالیز شده</b>
                                                    <i class="fas fa-thumbs-down  plusiconfont"></i>
                                                </a>
                                            </div>
                                            <!-- Response Modal -->
                                            <div class="modal fade" id="response" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xs">
                                                    <div class="modal-content">
                                                        <div class="modal-header py-3">
                                                            <h5 class="modal-title IR" id="newReviewLabel">تایید یا رد آنالیز</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p class="IR">آیا شما تصاویر آنالیز شده را تایید می نمایید؟</p>
                                                         </div>
                                                        <div class="modal-footer">
                                                            @if($ask->status == App\Enums\AnaliseStatus::response ||
                                                                $ask->status == App\Enums\AnaliseStatus::reject)
                                                            <form action="{{ route('website.account.analysis.accept',$ask) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('patch')
                                                                <button type="submit"  title="تایید" class="btn btn-primary px-8">
                                                                    تایید
                                                                    &nbsp;
                                                                    <i class="fas fa-thumbs-up  plusiconfont"></i>
                                                                </button>
                                                            </form>
                                                            @endif

                                                            @if($ask->status == App\Enums\AnaliseStatus::response ||
                                                             $ask->status == App\Enums\AnaliseStatus::accept)
                                                            <form action="{{ route('website.account.analysis.reject',$ask) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('patch')
                                                                <button type="submit"  title="رد" class="btn btn-danger px-8">
                                                                    رد
                                                                    &nbsp;
                                                                    <i class="fas fa-thumbs-down  plusiconfont"></i>
                                                                </button>
                                                            </form>
                                                            @endif
                                                            <button type="button" class="btn btn-secondary" title="انصراف" data-dismiss="modal">انصراف</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4"></div>
                                    </div>


                                @endif

                            <div class="row mt-5">
                                <div class="container">
                                    <label for="mySlides" class="control-label IRANYekanRegular"> تصاویر ارسالی شما:</label>
                                    @foreach($ask->images as $index=>$image)
                                    <div class="mySlides" id="mySlides">
                                        <div class="numbertext">{{ ++$index }} / {{ count($ask->images) }}</div>
                                        <a href="{{ $image->getImagePath()  }}" target="_blank" title="نمایش">
                                          <img src="{{ $image->getImagePath()  }}" style="width:100%;height: 500px;" title="{{ $image->getTitle() }}" alt="{{ $image->getTitle() }}">
                                        </a>
                                    </div>
                                    @endforeach

                                    <a class="prev" onclick="plusSlides(-1)">❮</a>
                                    <a class="next" onclick="plusSlides(1)">❯</a>

                                    <div class="caption-container">
                                        <p id="caption"></p>
                                    </div>

                                    <div class="row">
                                        @foreach($ask->images as $index=>$image)
                                            <div class="column">
                                                <img class="demo cursor" src="{{ $image->getImagePath()  }}" style="width:100%" onclick="currentSlide({{++$index}})" alt="{{ $image->title }}">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>


                    </div> <!-- end card -->
                </div> <!-- end col -->
            </div> <!-- end row -->

        </div>
    </div>
</div>
    <script>
        let slideIndex = 1;
        showSlides(slideIndex);

        function plusSlides(n) {
            showSlides(slideIndex += n);
        }

        function currentSlide(n) {
            showSlides(slideIndex = n);
        }

        function showSlides(n) {
            let i;
            let slides = document.getElementsByClassName("mySlides");
            let dots = document.getElementsByClassName("demo");
            let captionText = document.getElementById("caption");
            if (n > slides.length) {slideIndex = 1}
            if (n < 1) {slideIndex = slides.length}
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            slides[slideIndex-1].style.display = "block";
            dots[slideIndex-1].className += " active";
            captionText.innerHTML = dots[slideIndex-1].alt;
        }


        var load_rtc_interval = setInterval(function () {
            if (typeof RecordRTC == 'function') {
                clearInterval(load_rtc_interval);
                document.getElementById('record_btn_control').removeAttribute('style');

            }
        }, 1000);
        var audio = document.querySelector('audio');
        function captureMicrophone(callback) {
            if (microphone) {
                callback(microphone);
                return;
            }
            if (typeof navigator.mediaDevices === 'undefined' || !navigator.mediaDevices.getUserMedia) {
                alert('This browser does not supports WebRTC getUserMedia API.');
                if (!!navigator.getUserMedia) {
                    alert('This browser seems supporting deprecated getUserMedia API.');
                }
            }
            navigator.mediaDevices.getUserMedia({
                audio: isEdge ? true : {
                    echoCancellation: false
                }
            }).then(function (mic) {
                callback(mic);
            }).catch(function (error) {
                alert('Unable to capture your microphone. Please check console logs.');
                console.error(error);
            });
        }

        function replaceAudio(src) {
            var newAudio = document.createElement('audio');
            newAudio.controls = true;
            newAudio.autoplay = true;

            if (src) {
                newAudio.src = src;
            }

            var parentNode = audio.parentNode;
            parentNode.innerHTML = '';
            parentNode.appendChild(newAudio);

            audio = newAudio;
        }

        function stopRecordingCallback() {
            replaceAudio(URL.createObjectURL(recorder.getBlob()));

            btnStartRecording.disabled = false;

            setTimeout(function () {
                if (!audio.paused) return;

                setTimeout(function () {
                    if (!audio.paused) return;
                    audio.play();
                }, 1000);

                audio.play();
            }, 300);

            audio.play();
            btnSaveRecording.disabled = false;


        }

        var isEdge = navigator.userAgent.indexOf('Edge') !== -1 && (!!navigator.msSaveOrOpenBlob || !!navigator.msSaveBlob);
        var isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);

        var recorder; // globally accessible
        var microphone;

        var btnStartRecording = document.getElementById('btn-start-recording');
        var btnStopRecording = document.getElementById('btn-stop-recording');
        var btnSaveRecording = document.getElementById('btn-save-recording');
        var display = document.getElementById('display');
        var load = document.getElementById('load');


        btnStartRecording.onclick = function () {
            this.style.border = '';
            this.style.fontSize = '';
            display.style.display = "block";
            btnStopRecording.style.display = "block";
            btnStartRecording.style.display = "none";
            btnSaveRecording.style.display = "none";

            if (!microphone) {
                captureMicrophone(function (mic) {
                    microphone = mic;

                    if (isSafari) {
                        replaceAudio();

                        audio.muted = true;
                        audio.srcObject = microphone;

                        btnStartRecording.style.border = '1px solid red';
                        btnStartRecording.style.fontSize = '150%';

                        alert('Please click startRecording button again. First time we tried to access your microphone. Now we will record it.');
                        return;
                    }

                    click(btnStartRecording);
                });
                return;
            }

            replaceAudio();

            audio.muted = true;
            audio.srcObject = microphone;

            var options = {
                type: 'audio',
                numberOfAudioChannels: isEdge ? 1 : 2,
                checkForInactiveTracks: true,
                bufferSize: 16384
            };

            if (isSafari || isEdge) {
                options.recorderType = StereoAudioRecorder;
            }

            if (navigator.platform && navigator.platform.toString().toLowerCase().indexOf('win') === -1) {
                options.sampleRate = 48000; // or 44100 or remove this line for default
            }

            if (isSafari) {
                options.sampleRate = 44100;
                options.bufferSize = 4096;
                options.numberOfAudioChannels = 1;
            }

            if (recorder) {
                recorder.destroy();
                recorder = null;
            }
            options.numberOfAudioChannels = 1;
            options.recorderType = StereoAudioRecorder;
            recorder = RecordRTC(microphone, options);

            recorder.startRecording();

            btnStopRecording.disabled = false;
        };

        btnStopRecording.onclick = function () {

            display.style.display = "block";
            btnStopRecording.style.display = "none";
            btnStartRecording.style.display = "inline";
            btnSaveRecording.style.display = "inline";

            microphone.stop();
            microphone = null;

            recorder.stopRecording(stopRecordingCallback);
        };


        btnSaveRecording.onclick = function () {

            load.style.display = "inline";

            // this.disabled = true;
            if (!recorder || !recorder.getBlob()) return;


            if (isSafari) {
                recorder.getDataURL(function (dataURL) {

                    SaveToDisk(dataURL, getFileName('mp3'));
                });
                return;
            }

            var blob = recorder.getBlob();
            var file = new File([blob], getFileName('mp3'), {
                type: 'audio/mp3'
            });
            // invokeSaveAsDialog(file);

            var formData = new FormData();
            formData.append('new_voice_file', file);
            formData.append('new_voice_file_name',getFileName('mp3'));
            formData.append('_token',"{{ csrf_token() }}");

            $.ajax({
                url: "{{ route('admin.ask.analysis.voice',$ask)  }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                type: 'post',
                success: function(response) {
                    load.style.display = "none";
                    alert('پیام صوتی شما ثبت شد.');
                    location.reload();
                },
                error: function(response) {
                    load.style.display = "none";
                    alert('صدا شما ثبت نشد. لطفا مجددا تلاش نمایید.');
                }
            });

        };

        function click(el) {
            el.disabled = false; // make sure that element is not disabled
            var evt = document.createEvent('Event');
            evt.initEvent('click', true, true);
            el.dispatchEvent(evt);
        }

        function getRandomString() {
            if (window.crypto && window.crypto.getRandomValues && navigator.userAgent.indexOf('Safari') === -1) {
                var a = window.crypto.getRandomValues(new Uint32Array(3)),
                    token = '';
                for (var i = 0, l = a.length; i < l; i++) {
                    token += a[i].toString(36);
                }
                return token;
            } else {
                return (Math.random() * new Date().getTime()).toString(36).replace(/\./g, '');
            }
        }

        function getFileName(fileExtension) {
            var d = new Date();
            var year = d.getFullYear();
            var month = d.getMonth();
            var date = d.getDate();
            return 'bj-audio-' + year + month + date + '-' + getRandomString() + '.' + fileExtension;
        }

        function SaveToDisk(fileURL, fileName) {
            console.log(fileURL);
            // for non-IE
            if (!window.ActiveXObject) {
                var save = document.createElement('a');
                save.href = fileURL;
                save.download = fileName || 'unknown';
                save.style = 'display:none;opacity:0;color:transparent;';
                (document.body || document.documentElement).appendChild(save);

                if (typeof save.click === 'function') {
                    save.click();
                } else {
                    save.target = '_blank';
                    var event = document.createEvent('Event');
                    event.initEvent('click', true, true);
                    save.dispatchEvent(event);
                }

                (window.URL || window.webkitURL).revokeObjectURL(save.href);
            }

            // for IE
            else if (!!window.ActiveXObject && document.execCommand) {
                var _window = window.open(fileURL, '_blank');
                _window.document.close();
                _window.document.execCommand('SaveAs', true, fileName || fileURL)
                _window.close();
            }
        }
    </script>

@endsection
