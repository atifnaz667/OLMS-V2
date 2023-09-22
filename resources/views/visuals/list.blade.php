@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')
@section('title', 'Visuals')
@section('vendor-script')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
@endsection
<style>
    .pagination-nav {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
        margin-right: 20px;
    }
</style>

@section('content')
    @if (Session::has('status'))
      <input type="hidden" name="" id="tostStatus" value="{{ Session::get('status') }}">
      <input type="hidden" name="" id="tostMessage" value="{{ Session::get('message') }}">
      <input type="hidden" name="" id="tostType" value="{{ Session::get('status') == 'Success' ? 'text-success' : 'text-warning' }}">

      {{ Session::forget('status') }}
      {{ Session::forget('message') }}
    @endif
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Home/</span>
        Visuals
    </h4>

    <div id="visuals-div">


    @foreach ($topics as $topic)
      <input type="hidden" name="tpoic[]" class="topic_id" value="{{ $topic }}">
    @endforeach
@endsection



@section('page2-script')
    <script>
        let topics = [];
        $(document).ready(function(){
          $('.topic_id').each(function(i, obj) {
            topics[i] = $(this).val();
          });
          console.log(topics)
          function fetchVisualRecords(type = 'video') {
              var chapter_id = 10;
              let _token = '{{ csrf_token() }}'
              $.ajax({
                  url: '{{ route('get.visuals.ajax') }}',
                  method: 'POST',
                  data: {
                      chapter_id: chapter_id,
                      topics: topics,
                      visual_type: type,
                      _token: _token,
                  },
                  success: function(response) {
                      var visualsDiv = $('#visuals-div');
                      let chapters = response.chapters;
                      if (response.status === 'success') {
                        if (chapters && chapters.length > 0) {
                            $.each(chapters, function(index, chapter) {
                              let sr1 = index+1;
                                var row = `
                                    <h5>`+ sr1 +`. `+chapter.name+`</h5>`;

                                $.each(chapter.topics, function(i, topic) {
                                  let sr2 = i+1;
                                    row += `<div class="card mb-5" style="border-left: 3px solid #7367f0; border-radius:5px"> <h5 class="px-4 pt-4">` +sr1 +`.`+ sr2 +`. ` +topic.name+`</h5> <hr> <div class="row p-4">`;
                                    $.each(topic.visuals, function(j, visual) {
                                      let sr3 = j+1;
                                        row += `
                                            <div class="col-12 col-sm-6 col-md-4 mb-4">
                                                <iframe src="`+visual.path+`" style="height:260; width:100%" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                                                <h5 class="mt-1">`+ sr3 +`. `+ visual.title +`</h5>
                                            </div>`;
                                    });
                                    row += `</div></div>`;
                                });

                                row += ``;
                                visualsDiv.append(row);
                            });
                        }
                    } else {
                          console.error(response.message);
                      }

                  },
                  error: function(xhr, status, error) {
                      console.error(error);
                  }
              });
          }

          // Initial fetch and pagination UI update
          fetchVisualRecords();
        })
    </script>
@endsection
