<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    const COUNT_PER_PAGE = 20;

    private const _validation = [
        'message' => 'required|max:500',
        'author' => 'required|max:100',
        'source' => 'required|max:100',
        'source_link' => 'max:200',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quotes = Quote::paginate(self::COUNT_PER_PAGE);
        return view('quotes.index', ['quotes' => $quotes]);
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
        $quote = new Quote([
            'message' => $request->message, 'author' => $request->author, 'source' => $request->source, 'source_link' => $request->source_link,
        ]);
        $quote->save();
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
        $quote->fill([
            'message' => $request->message, 'author' => $request->author, 'source' => $request->source, 'source_link' => $request->source_link,
        ]);
        $quote->save();
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
        $quote->delete();
        return redirect(route('quotes.index'));
    }
}
