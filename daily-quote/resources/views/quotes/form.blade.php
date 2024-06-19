@csrf 
<dl class="form-list">
    <dt>Message</dt>
    <dd><textarea name="message" rows="5" cols="60">{{ old('message', $quote->message) }}</textarea></dd>
    <dt>Author</dt>
    <dd><input type="text" name="author" size="60" value="{{ old('author', $quote->author) }}"></dd>
    <dt>Source</dt>
    <dd><input type="text" name="source" size="60" value="{{ old('source', $quote->source) }}"></dd>
    <dt>Source link</dt>
    <dd><input type="text" name="source_link" size="60" value="{{ old('source_link', $quote->source_link) }}"></dd>
</dl>