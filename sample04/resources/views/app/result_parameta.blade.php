@extends('layouts.base')

@section('title','検索結果ページ')

@section('heading','検索結果')


@section('head-links')
<a href='/search'>検索ページへ戻る</a>
<br>
<a href='/index'>インデックスページへ戻る</a>
@endsection

@section('content1')

<p>method：{{$method}}</p>
<p>query：{{$q}}</p>


<form action="{{ route('export/csv') }}" method="get">
@csrf
	<fieldset>
	<input type="hidden" name="method" value="{{ $method }}" >
@if($method == "range")
	<input type="hidden" name="q1" value="{{ $q1 }}" >
	<input type="hidden" name="q2" value="{{ $q2 }}" >
@else
	<input type="hidden" name="q" value="{{ $q }}" >
@endif
	<label>検索結果をダウンロード
	<input type="submit" value="ダウンロード">
	</label>
	</fieldset>
</form>


<table border="1">
	<thead>
	<tr>
		<th>番号</th>
		<th>パラメータ名</th>
		<th>thresh</th>
		<th>max</th>
		<th>bs</th>
		<th>iteration</th>
		<th>ログ検索</th>
	</tr>
	</thead>

	<tbody>
@if(!isset($parametas[0]))
	<tr>
		<td colspan="5">NO DATA EXISTS!!</td>
	</tr>

@else
@foreach($parametas as $parameta)
	<tr>
		<td>{{$parameta["id"]}}</td>
		<td>{{$parameta["name"]}}</td>
		<td>{{$parameta["thresh"]}}</td>
		<td>{{$parameta["max"]}}</td>
		<td>{{$parameta["bs"]}}</td>
		<td>{{$parameta["iteration"]}}</td>
		<td>
			<form method="get" action="/search/paraName">
				<input type="hidden" name="method" value="{{ $method }}" />
				<input type="hidden" name="q" value="{{ $parameta['name'] }}" />
				<input type="submit"  value="この値で検索" />
			</form>
		</td>
	</tr>
@endforeach
@endif
	</tbody>
</table>

@endsection


@section('bottom-links')
<hr>
<a href='/search'>検索ページへ戻る</a>
<br>
<a href='/index'>インデックスページへ戻る</a>
@endsection
