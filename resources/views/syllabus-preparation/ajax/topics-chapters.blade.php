
  <input type="hidden" id="book_id" value="{{ $data['chapters'][0]->book_id ?? null }}">
  @foreach ($data['chapters'] as $key=> $chapter)
  <div class="col-sm-6">
    <div class="mb-2 p-2">
        <div class="form-check"><input class="form-check-input chapter-checkbox" type="checkbox"
                id="chapter_{{ $chapter->id }}" name="chapterId[]" value="{{ $chapter->id }}">
            <h5 class="form-check-h5" for="chapter_4">{{ $chapter->name }}</h5>
        </div>
        <div  class="row mb-4 ">
            <select id="topic_{{ $key }}" class="select2 form-select topic_ids" name='topics[]' multiple>
              @foreach ($chapter->topics as $topic)
                <option value="{{ $topic->id }}">{{ $topic->name }}</option>
              @endforeach
            </select>
        </div>
    </div>
  </div>
  @endforeach

