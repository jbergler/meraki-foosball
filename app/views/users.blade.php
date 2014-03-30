@extends('layouts.master')

@section('body')

    <style>
    table.table-count {
        counter-reset: rowNumber;
    }

    table.table-count tbody tr {
        counter-increment: rowNumber;
    }

    table.table-count tbody tr td:first-child::before {
        content: counter(rowNumber);
    }
    </style>

    <ul>
        @foreach ($games as $game)
            <li>{{ $game->created_at->diffForHumans() }} - <b>{{ $game->winner() }}</b> beat {{ $game->loser() }} - {{ $game->winnerScore() }} to {{ $game->loserScore() }}</li>
        @endforeach
    </ul>        

    <div class="row">
        <div class="col-lg-6">
            <h1>Singles</h1>

            <table class="table table-condensed table-striped table-count">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($singles as $user)
                    <tr>
                        <td></td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->rating_singles }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-lg-6">
            <h1>Doubles</h1>

            <table class="table table-condensed table-striped table-count">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($doubles as $user)
                    <tr>
                        <td></td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->rating_doubles }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop