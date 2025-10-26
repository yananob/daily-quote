<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Google\Cloud\Firestore\FirestoreClient;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    const COUNT_PER_PAGE = 10;

    private const _validation = [
        'message' => 'required|max:500',
        'author' => 'required|max:100',
        'source' => 'nullable|max:100',
        'source_link' => 'nullable|max:200',
    ];

    private $firestore;
    private $collection;

    public function __construct(FirestoreClient $firestore)
    {
        $this->firestore = $firestore;
        $this->collection = $this->firestore->collection('quotes');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $cursor = $request->input('cursor');

        $query = $this->collection->orderBy('message');

        if (!empty($keyword)) {
            $query = $query->where('message', '>=', $keyword)->where('message', '<=', $keyword . "\uf8ff");
        }

        if ($cursor) {
            $query = $query->startAfter([$cursor]);
        }

        $documents = $query->limit(self::COUNT_PER_PAGE)->documents();
        $quotes = [];
        $nextCursor = null;
        foreach ($documents as $document) {
            $quote = new Quote($document->data());
            $quote->id = $document->id();
            $quotes[] = $quote;
            $nextCursor = $document->data()['message'];
        }

        return view('quotes.index', compact('quotes', 'keyword', 'nextCursor'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('quotes.create', ['quote' => new Quote()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, self::_validation);
        $data = [
            'message' => $request->message, 'author' => $request->author, 'source' => $request->source, 'source_link' => $request->source_link,
        ];
        $this->collection->add($data);
        return redirect(route('quotes.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    // public function show(Quote $quote)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function edit(Quote $quote)
    {
        return view('quotes.edit', ['quote' => $quote]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Quote $quote)
    {
        $this->validate($request, self::_validation);
        $data = [
            'message' => $request->message, 'author' => $request->author, 'source' => $request->source, 'source_link' => $request->source_link,
        ];
        $this->collection->document($quote->id)->set($data, ['merge' => true]);
        return redirect(route('quotes.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quote $quote)
    {
        $this->collection->document($quote->id)->delete();
        return redirect(route('quotes.index'));
    }
}