@csrf
<dl class="form-list">
    <dt><label class="form-label">Message</label></dt>
    <dd><textarea name="message" class="form-control" rows="5">{{ old('message', $quote->message) }}</textarea></dd>
    <dt><label class="form-label">Author</label></dt>
    <dd><input type="text" name="author" class="form-control" value="{{ old('author', $quote->author) }}"></dd>
    <dt><label class="form-label">Source</label></dt>
    <dd><input type="text" name="source" class="form-control" value="{{ old('source', $quote->source) }}"></dd>
    <dt><label class="form-label">Source link</label></dt>
    <dd><input type="text" name="source_link" class="form-control" value="{{ old('source_link', $quote->source_link) }}"></dd>
</dl>