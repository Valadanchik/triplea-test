@extends('layouts.main')

@section('content')
    <form id="contact" action="{{ route('pay') }}" method="post">
        @csrf
        <h3>TripleA Form</h3>
        <fieldset>
            <input placeholder="Your name*" name="name" type="text" tabindex="1" required autofocus>
        </fieldset>
        <fieldset>
            <input placeholder="Your Email*" name="email" type="email" tabindex="2" required>
        </fieldset>
        <fieldset>
            <input placeholder="Your address" name="address" type="tel" tabindex="3">
        </fieldset>
        <fieldset>
            <input placeholder="Amount" name="amount" type="text" tabindex="3" required>
        </fieldset>
        <fieldset>
            <button type="submit" id="contact-submit" data-submit="...Sending">Submit</button>
        </fieldset>
    </form>
@endsection
